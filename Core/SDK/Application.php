<?php

namespace MauticPlugin\MauticAfricastalkingBundle\Core\SDK;

class Application extends Service {
	public function doFetchApplication() {
		$response = $this->client->get('user', ['query' => ['username' => $this->username]]);
		return $this->success($response);
	}

	public function fetchApplicationData() {
		return $this->doFetchApplication();
	}
}
