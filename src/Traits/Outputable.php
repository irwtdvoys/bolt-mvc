<?php
	namespace Bolt\Traits;

	use Bolt\Json;

	trait Outputable
	{
		public function toJson()
		{
			$results = null;
			$properties = $this->getProperties();

			foreach ($properties as $property)
			{
				$value = $this->{$property}();

				if ($value !== null)
				{
					if (is_array($value))
					{
						foreach ($value as &$element)
						{
							if (is_object($element) && get_class($element) != "stdClass")
							{
								$element = Json::decode($element->toJson());
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
							$value = Json::decode($value->toJson());
						}
					}

					if ($value !== null)
					{
						$results[$property] = $value;
					}
				}
			}

			return Json::encode($results);
		}
	}
?>
