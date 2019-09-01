<?php
	namespace Bolt;

	use Bolt\Interfaces\Connection;

	abstract class Model extends Base
	{
		protected $adapter;
		protected $hash;

		public function __construct(Connection $connection = null, $data = null)
		{
			$this->adapter($connection);

			if ($data !== null)
			{
				parent::__construct($data);
			}
		}

		public function adapter(Connection $connection = null)
		{
			if ($connection !== null)
			{
				$className = $this->className(false);
				$className = "App\\Adapters\\Models\\" . $className . "\\" . $connection->className(false);

				$this->adapter = new $className($connection, $this);

				return $this;
			}

			return $this->adapter;
		}

		public function load()
		{
			$data = $this->adapter->load();

			if ($data === false)
			{
				return false;
			}

			$this->populate($data);

			return $this;
		}

		public function save()
		{
			$hash = $this->calculateHash();

			if ($hash === $this->hash())
			{
				// no save required
				return $this;
			}

			$data = $this->adapter->save();

			if ($data === false)
			{
				return false;
			}

			if ($data !== true)
			{
				$this->populate($data);
			}

			$this->hash($hash);

			return $this;
		}

		private function calculateHash()
		{
			return md5(Json::encode($this));
		}
	}
?>
