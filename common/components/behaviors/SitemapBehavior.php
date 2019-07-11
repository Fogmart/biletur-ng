<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\behaviors;


class SitemapBehavior extends \himiklab\sitemap\behaviors\SitemapBehavior {

	public function generateSiteMap() {
		$result = [];
		$n = 0;

		/** @var \yii\db\ActiveRecord $owner */
		$owner = $this->owner;
		$query = $owner::find();
		if (is_array($this->scope)) {
			if (is_callable($this->owner->{$this->scope[1]}())) {
				call_user_func($this->owner->{$this->scope[1]}(), $query);
			}
		}
		else {
			if (is_callable($this->scope)) {
				call_user_func($this->scope, $query);
			}
		}

		$models = $query->all();
		foreach ($models as $model) {
			if (is_array($this->dataClosure)) {
				$urlData = call_user_func($this->owner->{$this->dataClosure[1]}(), $model);
			}
			else {
				$urlData = call_user_func($this->dataClosure, $model);
			}

			if (empty($urlData)) {
				continue;
			}

			$result[$n]['loc'] = $urlData['loc'];

			if (!empty($urlData['lastmod'])) {
				$result[$n]['lastmod'] = $urlData['lastmod'];
			}

			if (isset($urlData['changefreq'])) {
				$result[$n]['changefreq'] = $urlData['changefreq'];
			}
			elseif ($this->defaultChangefreq !== false) {
				$result[$n]['changefreq'] = $this->defaultChangefreq;
			}

			if (isset($urlData['priority'])) {
				$result[$n]['priority'] = $urlData['priority'];
			}
			elseif ($this->defaultPriority !== false) {
				$result[$n]['priority'] = $this->defaultPriority;
			}

			if (isset($urlData['news'])) {
				$result[$n]['news'] = $urlData['news'];
			}
			if (isset($urlData['images'])) {
				$result[$n]['images'] = $urlData['images'];
			}

			if (isset($urlData['xhtml:link'])) {
				$result[$n]['xhtml:link'] = $urlData['xhtml:link'];
			}

			++$n;
		}

		return $result;
	}
}