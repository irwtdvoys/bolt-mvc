<?php
	namespace Bolt;

	use ReflectionClass;
	use ReflectionMethod;

	abstract class Controller extends Base
	{
		public function __toString()
		{
			return "API Object: " . $this->className();
		}

		public function patchData($model, $parameters)
		{
			foreach ($parameters as $key => $value)
			{
				if (is_scalar($value) || $value === null)
				{
					$model->$key($value);
				}
				else
				{
					$model->$key = $this->patchData($model->$key, $value);
				}
			}

			return $model;
		}

		public function endpoints()
		{
			$class = new ReflectionClass($this->className(true));
			$allMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

			$methods = array();

			foreach ($allMethods as $next)
			{
				if ($next->name[0] == "_")
				{
					continue;
				}

				$methods[] = strtoupper($next->name);
			}

			return $methods;
		}
	}
?>
