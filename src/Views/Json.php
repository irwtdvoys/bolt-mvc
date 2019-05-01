<?php
	namespace Bolt\Views;

	use Bolt\Arrays;
	use Bolt\Base;
	use Bolt\Interfaces\View;

	class Json extends Base implements View
	{
		public function render($content)
		{
			header("Content-Type: application/json; charset=UTF-8");

			if ($content !== null)
			{
				echo($this->handleObject($content));
			}

			return true;
		}

		private function handleObject($content)
		{
			$type = Arrays::type($content);

			if ($type == "numeric")
			{
				$results = array();

				foreach ($content as $tmp)
				{
					if (is_scalar($tmp) || Arrays::type($tmp) !== false || get_class($tmp) == "stdClass")
					{
						$results = $content;
					}
					else
					{
						$results[] = \Bolt\Json::decode($tmp->toJson());
					}
				}

				return \Bolt\Json::encode($results);
			}
			elseif ($type != "assoc" && (is_object($content) && get_class($content) != "stdClass"))
			{
				if ($content instanceof \Bolt\Outputable || \method_exists($content, "toJson"))
				{
					return $content->toJson();
				}
				else
				{
					return \Bolt\Json::encode($content);
				}
			}
			else
			{
				return \Bolt\Json::encode($content);
			}
		}
	}
?>
