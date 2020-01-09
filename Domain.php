<?php
class Domain extends Query
	{
		private $clientId;
		private $period;
		private $noCheck;
		private $name;
		private $domainId;
		private $domainInfo;

		public function getDomainInfo()
		{
			return $this->domainInfo;
		}

		public function setDomainName($name)
		{
			$this->name = $name;
		}

		public function domainCreate($clientId, $period, $noCheck) 
		{
			$this->clientId = $clientId;
			$this->period = $period;
			$this->noCheck = $noCheck;
			$this->method = 'domainCreate';
			$this->params = array(
				'auth' => $this->auth,
				'clientId' => $this->clientId,
				'period' => $this->period,
				'noCheck' => $this->noCheck,
				'domain' => array(
					'name' => $this->name,
					'comment' => 'created via API'
				)
			);
			$this->sendQuery();
		}

		public function domainIdByName()
		{
			$this->method = 'domainEnum';
			if (strpos($this->name, '.рф') !== false)
			{
				$this->params = array(
					'auth' => $this->auth,
					'query' => array(
						'filter' => array(
							['nameIdn','=',$this->name]
						)
					)
				);
			} else {
				$this->params = array(
					'auth' => $this->auth,
					'query' => array(
						'filter' => array(
							['name','=',$this->name]
						)
					)
				);
			}
			$this->sendQuery();
			$res = json_decode($this->getAnswer(), true);
			$domainId = $res['result']['domains'][0]['id'];
			return $domainId;
		}

		public function domainInfo($id)
		{
			$this->method = 'domainInfo';
			$this->params = array(
				'auth' => $this->auth,
				'id' => $id
			);
			$this->sendQuery();
		}

		public function updateNameServers($domainId, $clientId, $nservers)
		{
			$this->method = 'domainUpdate';
			$this->params = array(
				'auth' => $this->auth,
				'id' => $domainId,
				'clientId' => $clientId,
				domain => array(
					'delegated' => true,
					'nservers' => $nservers
				)
			);
			$this->sendQuery();
		}
		public function checkName()
		{
			if (iconv_strlen($this->name) < 5)
				return false;
			if ((iconv_strlen($this->name) - 3) !== strpos($this->name, '.ru') && (iconv_strlen($this->name) - 3) !== strpos($this->name, '.рф') && (iconv_strlen($this->name) - 3) !== strpos($this->name, '.su'))
				return false;
			if ((strpos($this->name, '*') !== false) || strpos($this->name, '\\') !== false || strpos($this->name, '_') !== false || strpos($this->name, '@') !== false || strpos($this->name, '"') !== false || strpos($this->name, '#') !== false || strpos($this->name, '№') !== false || strpos($this->name, '$') !== false || strpos($this->name, ';') !== false  || strpos($this->name, ':') !== false  || strpos($this->name, '\'') !== false || strpos($this->name, '%') !== false || strpos($this->name, '^') !== false || strpos($this->name, '&') !== false || strpos($this->name, '?') !== false || strpos($this->name, '=') !== false || strpos($this->name, '+') !== false || strpos($this->name, ',') !== false || strpos($this->name, '<') !== false || strpos($this->name, '>') !== false || strpos($this->name, '|') !== false || strpos($this->name, '/') !== false)
				return false;
			return true;
		}
    }
?>