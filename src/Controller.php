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

		public function endpoints(): array
		{
			$class = new ReflectionClass($this->className(true));
			$allMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

			$methods = array();

			foreach ($allMethods as $next)
			{
				if ($next->name[0] == "_" || in_array($next->name, array("endpoints", "className", "patchData")))
				{
					continue;
				}

				$methods[] = strtoupper($next->name);
			}

			return $methods;
		}
	}
?>
