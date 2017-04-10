<?php

class UrlController
{
	public function actionIndex()
	{
		$view = new View();
		$view->content = $view->render('index');
		$view->display();
	}

	public function actionpush()
	{
		$url = Url::Instance();
		if (isset($_POST['urls'])) {
			$urls = explode(PHP_EOL, $_POST['urls']);

			echo json_encode($url->push($urls));
		}
	}

	public function actionlisten()
	{
		$url = Url::Instance();
		if (isset($_POST['id_order'])){
			$data = $url->parseUrlById(intval($_POST['id_order']));

			if (empty($data))
				echo json_encode(['status' => 'finished']);
			else echo $data;
		}
	}

	public function actionstatistics()
	{
		$url = Url::Instance();
		$view = new View();

		if (isset($_GET['url']) && !empty($_GET['url'])) {
			$view->stat = $url->getUrlStatistics($_GET['url'])[0];
			$view->statdays = $url->getUrlStatistics($_GET['url'])[1];
			$view->url = $_GET['url'];
			$view->content = $view->render('pagestat');

			$view->display();
		}
		else {
			$view->urls = $url->getAllAvailable();
			$view->content = $view->render('fullstatistics');
			$view->display();
		}
	}
}