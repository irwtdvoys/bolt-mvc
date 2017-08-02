<?php
	namespace Bolt;

	use Bolt\Interfaces\Connection;

	abstract class Model extends Base
	{
		protected $adapter;

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

				return true;
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

			return true;
		}

		public function save()
		{
			$data = $this->adapter->save();

			if ($data === false)
			{
				return false;
			}

			if ($data !== true)
			{
				$this->populate($data);
			}

			return true;
		}
	}
?>
