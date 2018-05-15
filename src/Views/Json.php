<?php
	namespace Bolt\Views;

	use Bolt\Arrays;
	use Bolt\Base;
	use Bolt\Interfaces\View;
	use Bolt\Outputable;

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
						$results[] = json_decode($tmp->toJson());
					}
				}

				return json_encode($results);
			}
			elseif ($type != "assoc" && (is_object($content) && get_class($content) != "stdClass"))
			{
				if ($content instanceof \Bolt\Outputable)
				{
					return $content->toJson();
				}
				else
				{
					return json_encode($content);
				}
			}
			else
			{
				return json_encode($content);
			}
		}
	}
?>
