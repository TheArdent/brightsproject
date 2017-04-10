<?php


class Url
{
	private static $instance;
	private $msql;

	/**
	 * @return Url
	 */

	public static function Instance()
	{
		if (self::$instance == null) {
			self::$instance = new Url();
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->msql = DB::GetInstance();
	}

	/**
	 * @param $urls
	 *
	 * @return array
	 */

	public function push($urls)
	{
		$data = [];
		foreach ($urls as $url) {
			$data[$this->msql->Insert('webstat', ['url' => $url, 'time' => time()])] = $url;
		}

		return $data;
	}

	/**
	 * @param $url
	 *
	 * @return array
	 */

	function parseUrl($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8']);

		$content = curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$http_code = $http_code ? $http_code : "This site can’t be reached";

		if (!curl_errno($ch)) {
			preg_match('/[<]title[>]([^<]*)[<][\/]title[>]/i', $content, $matches);
		}
		$title = isset($matches[1]) ? $matches[1] : "";

		curl_close($ch);

		return [
			'url'   => $url,
			'code'  => trim($http_code),
			'title' => $title
		];
	}

	/**
	 * @param $id
	 *
	 * @return array|string
	 */

	public function parseUrlById($id)
	{
		$row = $this->msql->Select("SELECT * FROM webstat WHERE id = {$id} LIMIT 1");

		if (empty($row)) {
			return [];
		}
		else {
//			sleep(1);
			$url_data = $this->parseUrl($row[0]['url']);
			$this->msql->Update('webstat', $url_data, "id = {$id}");
			$url_data['id'] = $id;
			$data = json_encode($url_data);

			if (json_last_error()) {
				$url_data["title"] = "Invalid encoding";

				return json_encode($url_data);
			}
			else {
				return $data;
			}
		}
	}

	/**
	 * @return array
	 */

	public function getAllAvailable()
	{
		return $this->msql->Select('SELECT * FROM webstat WHERE code IS NOT NULL ORDER BY id DESC');
	}

	public function getUrlStatistics($url)
	{
		$data = $this->msql->Select("SELECT * FROM webstat WHERE url = '{$url}' and code IS NOT NULL");

		$group = [];
		$difdays = [];

		foreach ($data as $item) {
			if (!in_array(date('d', $item['time']), $difdays)) {
				$difdays[] = date('d', $item['time']);
			}
		}

		foreach ($data as $item) {
			foreach ($difdays as $day) {
				$group[$item['code']][$day] = 0;
			}
		}
		foreach ($group as $key => $value) {
			;
			foreach ($data as $element) {
				if ($element['code'] == $key && $value[date('d', $element['time'])] !== false) {
					$group[$key][date('d', $element['time'])]++;
				}
			}
		}

		return [ $group, $difdays];
	}
}