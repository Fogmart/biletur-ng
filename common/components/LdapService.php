<?php
namespace common\components;

use Adldap\Adldap;
use Adldap\Auth\BindException;
use Adldap\Auth\PasswordRequiredException;
use Adldap\Auth\UsernameRequiredException;
use Adldap\Connections\ProviderInterface;
use Adldap\Models\Group;
use Adldap\Models\Model;
use Adldap\Models\User;
use Illuminate\Support\Collection;
use yii\base\Component;

/**
 * Class LdapService
 * Wrapper to work with LDAP package.
 *
 * @package app\components
 */
class LdapService extends Component
{
	/**
	 * Account suffix.
	 *
	 * @var string
	 */
	public $accountSuffix = '';
	/**
	 * List of domain controllers.
	 *
	 * @var array
	 */
	public $domainControllers = [];
	/**
	 * Base DN.
	 *
	 * @var string
	 */
	public $baseDn;
	/**
	 * Domain admin user name.
	 *
	 * @var string
	 */
	public $adminUserName;
	/**
	 * Domain admin password.
	 *
	 * @var string
	 */
	public $adminPassword;
	/**
	 * Connection provider to the AD.
	 *
	 * @var ProviderInterface
	 */
	private $provider;

	/**
	 * Attempt to login in domain.
	 *
	 * @param string $login    login.
	 * @param string $password password.
	 *
	 * @throws BindException if there are connections errors.
	 * @throws PasswordRequiredException
	 * @throws UsernameRequiredException
	 *
	 * @return bool
	 */
	public function attempt(string $login, string $password): bool
	{
		return $this->getProvider()
			->auth()
			->attempt($login, $password);
	}

	/**
	 * Find user in the AD by login(account name).
	 *
	 * @param string $login login/account name.
	 *
	 * @throws \Adldap\Models\ModelNotFoundException
	 * @throws BindException
	 * @throws \InvalidArgumentException
	 * @return User|Model
	 */
	public function findUserByLogin(string $login): User
	{
		return $this->getProvider()
			->search()
			->users()
			->where('samaccountname', '=', $login)
			->firstOrFail();
	}

	/**
	 * Find user in the AD by full name.
	 *
	 * @param string $username users full name.
	 *
	 * @throws \Adldap\Models\ModelNotFoundException
	 * @throws BindException
	 * @throws \InvalidArgumentException
	 * @return User|Model
	 */
	public function findUserByFullName(string $username): User
	{
		return $this->getProvider()
			->search()
			->users()
			->where('cn', '=', $username)
			->firstOrFail();
	}

	/**
	 * Returns list of all group in the AD.
	 *
	 * @throws BindException
	 * @return Collection|Group[]
	 */
	public function getAllGroups(): Collection
	{
		return $this->getProvider()
			->search()
			->groups()
			->get();
	}

	/**
	 * Returns configured AD provider.
	 *
	 * @throws BindException
	 * @return ProviderInterface
	 */
	private function getProvider(): ProviderInterface
	{
		if (null === $this->provider) {
			$ad = new Adldap($this->getConfig());
			$this->provider = $ad->connect('default');
		}

		return $this->provider;
	}

	/**
	 * Returns config to connect to the ldap domain.
	 *
	 * @return array
	 */
	private function getConfig(): array
	{
		return [
			'default' => [
				'domain_controllers' => $this->domainControllers,
				'base_dn'            => $this->baseDn,
				'admin_username'     => $this->adminUserName,
				'admin_password'     => $this->adminPassword,
				'account_suffix'     => $this->accountSuffix,
			],
		];
	}
}