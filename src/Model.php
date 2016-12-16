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

		public function toJson()
		{
			$results = null;
			$properties = $this->getProperties();

			foreach ($properties as $property)
			{
				$value = $this->{$property->name};

				#if ($value !== null)
				{
					if (is_array($value))
					{
						foreach ($value as &$element)
						{
							if (is_object($element) && get_class($element) != "stdClass")
							{
								$element = json_decode($element->toJson());
							}
						}

						if ($value === array())
						{
							$value = null;
						}
					}

					if (is_object($value))
					{
						if (get_class($value) != "stdClass")
						{
							$value = json_decode($value->toJson());
						}
					}

					#if ($value !== null)
					{
						$results[$property->name] = $value;
					}
				}
			}

			return json_encode($results);
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
			return $this->adapter->save();
		}
	}
?>
