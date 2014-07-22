<?php 
/**
 * @author Raymart Marasigan
 * @date 3/27/2014
 * @des - this class can automatically render a dropdown picker you want. It uses no sql for better performance
 * @dependencies - citizenship, countries, language, religion file
 * 
 */

class dropdown_renderer
{
	
	private static $data_path   = "Project/1_Website/Code/Modules/pickers/"; //path of the dependencies file
	private static $year_start  = 1970; //for date picker
	private static $year_end    = 2030; //for date picker
	
	//see http://php.net/manual/en/function.date.php for reference
	private static $month_format = "M";
	
	/**
	 * @des - this function make a select input
	 * @params array $values - an array consisting of values to be dispalyed in dropdwons e.g. array([0] => array("PH" => "Philippines"))
	 * @params string $name  - the name attribute of the select tags e.g. <select name="imat">
	 * @params string $first_value - it is the first value of dropdown (normally used as a placeholder)
	 * @params string $selected_value - a defautl/selected value in dropdown
	 * @params string $class - class attribute of the select tag e.g. <select class="imat">
	 * 
	 * @return - html select input
	 * 
	 */
	
	public static function makeSelectInput($values, $name, $first_value = NULL, $selected_value = NULL, $class = "")
	{
		$dropdown = '<select name="'.$name.'" class="'.$class.'">';
		$dropdown .= "<option value=''>$first_value</option>";
	
		if($selected_value == NULL)
			foreach($values as $value)
				$dropdown .= '<option value="'.$value.'">'.$value.'</option>';
		else
		{
			foreach($values as $value)
			{
				if($value == $selected_value)
					$dropdown .= '<option  selected="selected" value="'.$value.'">'.$value.'</option>';
				else
					$dropdown .= '<option value="'.$value.'">'.$value.'</option>';
			}
		}

		$dropdown .= "</select>";

		return $dropdown;
	}
	
	
	//-------------------------------------------------------------------------------------------------------------------
	
	/**
	* @params array $selected_values - an array consisting of selected values to be
	* dispalyed in dropdwons e.g. array("months" => "january", "days" => "1", "year" => "2014")
	* @params string $name  - the name attribute of the select tags e.g. <select name="imat">
	* 
	* @return - date picker
	*/
	
	public static function makeDatePicker($name, $class = NULL, $first_value = array("months" => "MMM", "days" => "DD", "years" => "YYY"), $selected_values = NULL)
	{
		//first value
		$first_dd = (isset($first_value["days"])) ? $first_value["days"] : "";
		$first_mm = (isset($first_value["months"])) ? $first_value["months"] : "";
		$first_yy = (isset($first_value["years"])) ? $first_value["years"] : "";
		
		//selected value
		$selected_dd = (isset($selected_values["days"])) ? $selected_values["days"] : "";;
		$selected_mm = (isset($selected_values["months"])) ? $selected_values["months"] : "";;
		$selected_yy = (isset($selected_values["years"])) ? $selected_values["years"] : "";;
		
		//class
		$class_dd = (isset($class["days"])) ? $class["days"] : "";
		$class_mm = (isset($class["months"])) ? $class["months"] : "";
		$class_yy = (isset($class["years"])) ? $class["years"] : "";
		
		$date_picker   = self::makeSelectInput(range(1, 31),                  $name."_days",   $first_dd, $selected_dd, $class_dd);
		$date_picker  .= self::makeSelectInput(self::getArrayItems("months"), $name."_months", $first_mm, $selected_mm, $class_mm);
		$date_picker  .= self::makeSelectInput(self::getArrayItems("years"),  $name."_years",  $first_yy, $selected_yy, $class_yy);
		
		return $date_picker;
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	/**
	* @params string $selected_value - selected value to be dispalyed in dropdwons
	* @params string $name           - the name attribute of the select tags e.g. <select name="imat">
	* @params string $first_value    - first value of dropdown
	* @params string $class - class attribute of the select tag e.g. <select class="imat">
	*
	* @return - country picker
	*/
	
	public static function makeCountryPicker($name, $first_value = NULL, $selected_value = NULL, $class = NULL)
	{
		$countries = array();
		foreach(self::getArrayItems("countries") as $country)
			$countries[$country["country"]] = $country["country"];
		
		$country_picker  = self::makeSelectInput($countries, $name, $first_value, $selected_value);
		return $country_picker;
	}
	
	//-------------------------------------------------------------------------------------------------------------------

	
	/**
	* @params string $selected_value - selected value to be dispalyed in dropdwons
	* @params string $name           - the name attribute of the select tags e.g. <select name="imat">
	* @params string $first_value    - first value of dropdown
	* @params string $class - class attribute of the select tag e.g. <select class="imat">
	*
	* @return - citizenship picker
	*/
	
	public static function makeCitizenshipPicker($name, $first_value = NULL, $selected_value = NULL, $class = NULL)
	{
		$citizenship_picker  = self::makeSelectInput(self::getArrayItems("citizenship"), $name, $first_value, $selected_value, $class);
		return $citizenship_picker;
	}
	
	//-------------------------------------------------------------------------------------------------------------------

	
	/**
	* @params string $selected_value - selected value to be dispalyed in dropdwons
	* @params string $name           - the name attribute of the select tags e.g. <select name="imat">
	* @params string $first_value    - first value of dropdown
	* @params string $class - class attribute of the select tag e.g. <select class="imat">
	* 
	* @return - religion picker
	*/
	
	public static function makeReligionPicker($name, $first_value = NULL, $selected_value = NULL, $class = NULL)
	{
		$religion_picker  = self::makeSelectInput(self::getArrayItems("religions"), $name, $first_value, $selected_value, $class);
		return $religion_picker;
	}
	
	
	//-------------------------------------------------------------------------------------------------------------------

	/**
	* @params string $selected_value - selected value to be dispalyed in dropdwons
	* @params string $name           - the name attribute of the select tags e.g. <select name="imat">
	* @params string $first_value    - first value of dropdown
	* @params string $class - class attribute of the select tag e.g. <select class="imat">
	* @return - language picker
	* 
	*/
	
	public static function makeLanguagePicker($name, $first_value = NULL, $selected_value = NULL, $class = NULL)
	{
		$languages = array();
		
		foreach(self::getArrayItems("language") as $language)
			$languages[$language["language"]] = $language["language"];
		
		$language_picker  = self::makeSelectInput($languages, $name, $first_value, $selected_value, $class);
		return $language_picker;
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	/**
	 * @des - this will provide the resources you want
	 * @params string $index - the name of resources you want
	 * @return - array of resources
	 */
	
	private static function getArrayItems($index)
	{
		switch($index)
		{
			case "months" :
				{
					$months = array();
					for( $i=1; $i <= 12; $i++ )
						$months[] = date(self::$month_format, strtotime("01-$i-2013"));
	
					return $months;
				} break;
					
			case "years" :
				{
					$years = array();
					for( $i = self::$year_start; $i <= self::$year_end; $i++ )
						$years[] = $i;
						
					return $years;
				}
			case "countries" :
				{
					$result	=	file_get_contents(self::$data_path . "countries");
					if(function_exists("gzdecode"))
					return json_decode(gzdecode($result), TRUE);
					else
					return json_decode(gzinflate(substr($result,10,-8)));
				}
			case "citizenship":
				{
					$result	=	file_get_contents(self::$data_path . "citizenship");
					if(function_exists("gzdecode"))
					return json_decode(gzdecode($result), TRUE);
					else
					return json_decode(gzinflate(substr($result,10,-8)));
				}
			case "religions":
				{
					$result	=	file_get_contents(self::$data_path . "religion");
					if(function_exists("gzdecode"))
					return json_decode(gzdecode($result), TRUE);
					else
					return json_decode(gzinflate(substr($result,10,-8)));
				}
			case "language" :
				{
					$result	=	file_get_contents(self::$data_path . "language");
					if(function_exists("gzdecode"))
					return json_decode(gzdecode($result), TRUE);
					else
					return json_decode(gzinflate(substr($result,10,-8)));
				}
			default:
				return NULL;
		}
	}
}