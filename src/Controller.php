<?php
	namespace Bolt;

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
	}
?>
