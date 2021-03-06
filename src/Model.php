<?php
	namespace Bolt;

	use Bolt\Exceptions\Output;
	use Bolt\Interfaces\Connection;

	abstract class Model extends Base
	{
		protected ?Adapter $adapter = null;
		protected string $hash;

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

		public function load(): self
		{
			$data = $this->adapter->load();

			if ($data === false)
			{
				throw new Output($this->className(false) . " not found", 404);
			}

			$this->populate($data);

			return $this;
		}

		public function save(): self
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
				throw new Output("Error saving " . $this->className(false), 500);
			}

			if ($data !== true)
			{
				$this->populate($data);
			}

			$this->hash($hash);

			return $this;
		}

		private function calculateHash(): string
		{
			return md5(Json::encode($this));
		}
	}
?>
