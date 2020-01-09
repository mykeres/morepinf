<?php
	/* INI-loading resources */
	if (preg_match('!\/static\/(css|js|images|fonts)\/.*?\.(css|js|vue|svg|png|gif|ttf|woff|otf|eot)$!',$_SERVER['REQUEST_URI'],$m)) {
		$m[0] = 'resources/'.urldecode($m[0]);
		if (!file_exists($m[0])) {exit;}
		$path = realpath($m[0]);
		$must = realpath($_SERVER['DOCUMENT_ROOT'].'/resources/static/');
		if (strpos($path,$must) !== 0) {exit;}
		switch ($m[2]) {
			case 'css':header('Content-type: text/css');ob_start('ob_gzhandler');break;
			case 'js':
			case 'vue':
				header('Content-type: application/javascript');ob_start('ob_gzhandler');break;
			case 'svg':header('Content-type: image/svg+xml');ob_start('ob_gzhandler');break;
			case 'png':header('Content-type: image/png');break;
			case 'gif':header('Content-type: image/gif');break;
			case 'ttf':
			case 'woff':
			case 'otf':
			case 'eot':
				header('Content-type: application/x-unknown-content-type');break;
			default: exit;
		}
		readfile($m[0]);exit;
	}
	/* END-loading resources */

	$params = parse_url($_SERVER['REQUEST_URI']);
	$params = $params['path'] ?? '';

	trait __render{
		private $_render_replaces = 0;
		private $_render_cache = [];
		function _render_find_keyword($kword,$pool = false){
			if ($pool === false) {$pool = $this->output;}
			if (isset($pool[$kword])) {return $pool[$kword];}
			$parts = explode('.',$kword);
			$rest  = [];
			while (($pop = array_pop($parts)) !== null) {
				$rest[] = $pop;
				$try = implode('.',$parts);
				if (isset($pool[$try])) {
					if( !$rest ){return $pool[$try];}
					$rest = array_reverse($rest);
					$rest = implode('.',$rest);
					return $this->_render_find_keyword($rest,$pool[$try]);
				}
			}
			return false;
		}
		function _render_replace_includes($blob){
			$regexp = '!{{@(?<template>[a-zA-Z0-9_\.\-/]+)}}!sm';
			$this->_render_replaces = 0;
			while (preg_match($regexp,$blob,$m)) {
				$this->_render_replaces++;
				if ($this->_render_replaces > 20) {echo 'max replaces';exit;}
				$blob = preg_replace_callback($regexp,function($m){
					return $this->_render_snippet($m['template']);
				},$blob);
			}
			return $blob;
		}
		function _render_replace_logic($blob,$pool = false,$reps = false){
			$this->_render_replaces = 0;
			while (preg_match('/{{[#\^]([a-zA-Z0-9_\.\-]+)}}(.*?){{\/\1}}/sm',$blob)) {
				$this->_render_replaces++;
				if ($this->_render_replaces > 20) {break;}
				$blob = preg_replace_callback('!{{(?<operator>[#\^])(?<word>[a-zA-Z0-9_\.\-]+)}}(?<snippet>.*?){{/\2}}!sm',function($m) use (&$pool){
					$word = $this->_render_find_keyword($m['word'],$pool);
					if (!$word) {
						if ($m['operator'] == '^') {return $m['snippet'];}
						return '';
					}
					if ($m['operator'] == '^') {return '';}
					/* INI-Support for Arrays */
					if (is_array($word)) {
						$blob    = '';
						$snippet = $m['snippet'];
						foreach ($word as $elem) {
							if (!is_array($elem)) {$elem = ['.'=>$elem];}
							$blob .= $this->_render_replace($snippet,$elem);
						}
						return $blob;
					}
					/* END-Support for Arrays */
					return $m['snippet'];
				},$blob);
			}
			return $blob;
		}
		function _render_snippet($s = false,$pool = false,$sname = false){
			$file = $this->_templates['path'].$s.'.'.$this->_templates['ext'];
			if (!isset($this->_render_cache[$s])) {
				if (!file_exists($file)) {return false;}
				ob_start();include($file);$blob = ob_get_contents();ob_end_clean();
				$this->_render_cache[$s] = $blob;
			}
			if (empty($blob)) {$blob = $this->_render_cache[$s];}
			if ($pool) {$blob = $this->_render_replace($blob,$pool);}
			return $blob;
		}
		function _render_replace($blob,$pool = false,$reps = false){
			$blob = $this->_render_replace_includes($blob);
			$blob = $this->_render_replace_logic($blob,$pool,$reps);
			$this->_render_replaces = 0;
			$notFound = [];
			while (($hasElems = preg_match_all('/{{[a-zA-Z0-9_\.\-]+}}/',$blob,$reps))) {
				$this->_render_replaces++;
				$reps = array_unique($reps[0]);
				foreach ($reps as $k=>$rep) {
					$kword = substr($rep,2,-2);
					if (($word = $this->_render_find_keyword($kword,$pool)) === false) {
						unset($reps[$k]);
						$notFound[$kword] = '';
						continue;
					}
					//if( is_array($word) ){unset($reps[$k]);$word = print_r($word,1);}
					if (is_array($word)) {unset($reps[$k]);$word = '';continue;}
					$blob = str_replace($rep,$word,$blob);
					$blob = $this->_render_replace_includes($blob);
					$blob = $this->_render_replace_logic($blob,$pool,$reps);
					continue;
				}
				if (!$reps) {break;}
				if ($this->_render_replaces > 20) {print_r($notFound);print_r($reps);exit;}
			}
			return $blob;
		}
	}

	class _controller{
		public $_output = '';
		public $_templates = [
			 'path'=>'views/'
			,'ext'=>'php'
		];
		public $output = []; /* Replacements for template */
		use __render;
		protected function _render($t){
			$path = $this->_templates['path'].$t.'.'.$this->_templates['ext'];
			$base = $this->_templates['path'].'base.'.$this->_templates['ext'];
			if ($this->_templates['ext'] == 'php') {
				ob_start();include($path);$this->output['__MAIN__'] = ob_get_contents();ob_end_clean();
				ob_start();include($base);$this->_output = ob_get_contents();ob_end_clean();
			}else{
				if ($t) {$this->output['__MAIN__'] = file_get_contents($path);}
				$this->_output = file_get_contents($base);
			}

			$this->_output = $this->_render_replace($this->_output);
			$this->_output = preg_replace('/{{[a-zA-Z0-9_\.\-]+}}/','',$this->_output);
		}
	}

	class _dispatcher{
		private $base = '';
		private $params = [];
		function __construct($base = '',$params = []) {
			$this->base = $base;
			$this->params = $params;
			$this->controller();
		}
		function controller(){
			do {
				/* Get the callback */
				$controller = array_shift($this->params);
				if ($controller == NULL) {$controller = 'index';}
				$controllerPath = $this->base.str_replace('-','_',$controller).'.php';
				if (!file_exists($controllerPath)) {
					array_unshift($this->params,$controller);
					$controller = 'index';
					$controllerPath = $this->base.$controller.'.php';
				}

				include_once($controllerPath);
				$controller = '_controller_'.$controller;
				$controller = new $controller();

				$command = $unshift = array_shift($this->params);
				$command = str_replace('-','_',$command);
				if ($command == NULL) {
					$command = 'main';
					break;
				}

				if (method_exists($controller,$command)) {break;}
				if (isset($unshift)) {array_unshift($this->params,$unshift);}
				$command = 'main';
			} while(false);

			$controller->$command(...$this->params);
			echo $controller->_output;
			exit;
		}
	}

	chdir($_SERVER['DOCUMENT_ROOT'].'/resources/');
	include_once('init.php');
	$base = $_SERVER['DOCUMENT_ROOT'].'/resources/controllers/';
	$params = explode('/',$params);
	$params = array_diff($params,['']);

	new _dispatcher($base,$params);
	exit;
