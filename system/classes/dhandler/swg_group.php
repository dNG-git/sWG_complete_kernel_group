<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
secured WebGine
net-based application engine
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?swg

The following license agreement remains valid unless any additions or
changes are being made by direct Netware Group in a written form.

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;gpl
----------------------------------------------------------------------------
#echo(sWGkernelGroupVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* OOP (Object Oriented Programming) requires an abstract data
* handling. The sWG is OO (where it makes sense).
*
* @internal   We are using phpDocumentor to automate the documentation process
*             for creating the Developer's Manual. All sections including
*             these special comments will be removed from the release source
*             code.
*             Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage kernel
* @uses       direct_product_iversion
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Basic configuration

/* -------------------------------------------------------------------------
Direct calls will be honored with an "exit ()"
------------------------------------------------------------------------- */

if (!defined ("direct_product_iversion")) { exit (); }

//j// Functions and classes

/* -------------------------------------------------------------------------
Testing for required classes
------------------------------------------------------------------------- */

$g_continue_check = true;
if (defined ("CLASS_direct_group")) { $g_continue_check = false; }
if (!defined ("CLASS_direct_data_handler")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_group
/**
* This abstraction layer provides group specific functions.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage kernel
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/
class direct_group extends direct_data_handler
{
/**
	* @var array $group_rights Group rights cache
*/
	protected $class_rights;
/**
	* @var boolean $data_subs_allowed True if the next "dclass_update ()" call
	*      is an insert - the code is the same.
*/
	protected $data_insert_mode;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_group->__construct () and direct_group->direct_group ()
/**
	* Constructor (PHP5) __construct (direct_group)
	*
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -group_handler->__construct (direct_group)- (#echo(__LINE__)#)"); }

		if (!defined ("CLASS_direct_right")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_right.php"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about available functions 
------------------------------------------------------------------------- */

//		$this->functions['delete'] = true;
		$this->functions['get_aid'] = true;
		$this->functions['get_rights'] = defined ("CLASS_direct_right");
		$this->functions['right_add'] = defined ("CLASS_direct_right");
//		$this->functions['right_delete'] = defined ("CLASS_direct_right");
		$this->functions['set_insert'] = true;
		$this->functions['set_right'] = defined ("CLASS_direct_right");
		$this->functions['set_update'] = true;
		$this->functions['update'] = true;

/* -------------------------------------------------------------------------
Set up the cache
------------------------------------------------------------------------- */

		$this->class_rights = array ();
		$this->data = array ();
		$this->dvar_data_insert_mode = false;
	}

	//f// direct_right->get ($f_gid = "",$f_load = true)
/**
	* Reads in a group entry with the specified ID.
	*
	* @param  string $f_gid Group ID
	* @param  boolean $f_load Load group data from the database
	* @uses   direct_debug()
	* @uses   direct_group::get_aid()
	* @uses   USE_debug_reporting
	* @return mixed Right array; false on error
	* @since  v0.1.00
*/
	public function get ($f_gid = "",$f_load = true)
	{
		if (USE_debug_reporting) { direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->get ($f_gid,+f_load)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ($f_load) { $f_return = $this->get_aid (NULL,$f_gid); }
		elseif (strlen ($f_gid))
		{
			$this->data = array ("ddbgroups_id" => $f_gid);
			$f_return = $this->data;
		}

		return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -group_handler->get ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_right->get_aid ($f_attributes = NULL,$f_values = "")
/**
	* Reads in a right entry with custom attribute. Please note that only
	* attributes of type "string" are supported.
	*
	* @param  mixed $f_attributes Attribute name(s) (array or string)
	* @param  mixed $f_values Attribute value(s) (array or string)
	* @uses   direct_db::define_attributes()
	* @uses   direct_db::define_join()
	* @uses   direct_db::define_limit()
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_db::init_select()
	* @uses   direct_db::query_exec()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return mixed Right array; false on error
	* @since  v0.1.00
*/
	public function get_aid ($f_attributes = NULL,$f_values = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -group_handler->get_aid (+f_attributes,+f_values)- (#echo(__LINE__)#)"); }

		if (!isset ($f_attributes)) { $f_attributes = $direct_settings['group_table'].".ddbgroups_id"; }
		$f_return = false;

		if ((is_string ($f_attributes))&&(is_string ($f_values)))
		{
			$f_attributes = array ($f_attributes);
			$f_values = array ($f_values);
		}
		elseif ((!is_array ($f_attributes))||(!is_array ($f_values))||(count ($f_attributes) != (count ($f_values)))) { $f_attributes = NULL; }

		if (isset ($f_attributes))
		{
			if (count ($this->data) > 1) { $f_return = $this->data; }
			elseif ((($f_values == NULL)&&(!empty ($this->data_extra_conditions)))||(isset ($f_attributes)))
			{
				$direct_classes['db']->init_select ($direct_settings['group_table']);

				$direct_classes['db']->define_attributes ($direct_settings['group_table'].".*");

				$f_select_criteria = "<sqlconditions>";

				if (isset ($f_attributes,$f_values))
				{
					foreach ($f_values as $f_value)
					{
						$f_attribute = array_shift ($f_attributes);
						$f_select_criteria .= $direct_classes['db']->define_row_conditions_encode ($f_attribute,$f_value,"string");
					}
				}

				$f_select_criteria .= "</sqlconditions>";

				$direct_classes['db']->define_row_conditions ($f_select_criteria);

				$direct_classes['db']->define_limit (1);
				$this->data = $direct_classes['db']->query_exec ("sa");

				if ($this->data) { $f_return = $this->data; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->get_aid ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_group->get_rights ()
/**
	* Returns all rights for this group.
	*
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function get_rights ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "name-asc")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/system/classes/dhandler/swg_contentor_cat.php -contentor_cat_handler->dclass_get_docs ($f_offset,$f_perpage,$f_sorting_mode,+f_frontpage_mode)- (415)"); }

		$f_return = array ();

		if ($this->data)
		{
			$f_cache_signature = md5 ($this->data['ddbgroups_id'].$f_offset.$f_perpage.$f_sorting_mode);

			if (isset ($this->class_rights[$f_cache_signature])) { $f_return =& $this->class_rights[$f_cache_signature]; }
			else
			{
				$direct_classes['db']->init_select ($direct_settings['group_connect_table']);
				$direct_classes['db']->define_attributes (array ($direct_settings['group_rights_table'].".ddbgrights_id",$direct_settings['group_rights_table'].".ddbgrights_name",$direct_settings['group_rights_table'].".ddbgrights_rid",$direct_settings['group_rights_table'].".ddbgrights_setup"));
				$direct_classes['db']->define_join ("left-outer-join",$direct_settings['group_rights_table'],"<sqlconditions><element1 attribute='{$direct_settings['group_rights_table']}.ddbgrights_id' value='{$direct_settings['group_connect_table']}.ddbgconnect_target_id' type='attribute' /></sqlconditions>");
				$direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_connect_table'].".ddbgconnect_source_id",$this->data['ddbgroups_id'],"string"))."<element1 attribute='{$direct_settings['group_connect_table']}.ddbgconnect_target_type' value='r' type='string' /></sqlconditions>");

				switch ($f_sorting_mode)
				{
				case "name-desc":
				{
					$f_select_criteria = "<sqlordering><element1 attribute='{$direct_settings['group_connect_table']}.ddbgroups_name' type='desc' /></sqlordering>";
					break 1;
				}
				case "id-asc":
				{
					$f_select_criteria = "<sqlordering><element1 attribute='{$direct_settings['group_connect_table']}.ddbgconnect_target_id' type='asc' /></sqlordering>";
					break 1;
				}
				case "id-desc":
				{
					$f_select_criteria = "<sqlordering><element1 attribute='{$direct_settings['group_connect_table']}.ddbgconnect_target_id' type='desc' /></sqlordering>";
					break 1;
				}
				default: { $f_select_criteria = "<sqlordering><element1 attribute='{$direct_settings['group_connect_table']}.ddbgroups_name' type='asc' /></sqlordering>"; }
				}

				$direct_classes['db']->dclass_define_ordering ($f_select_criteria);

				if (is_numeric ($f_perpage))
				{
					$direct_classes['db']->dclass_define_limit ($f_perpage);
					$direct_classes['db']->dclass_define_offset ($f_offset);
				}

				$f_results_array = $direct_classes['db']->query_exec ("ma");

				if ($f_results_array)
				{
					foreach ($f_results_array as $f_result_array)
					{
						$this->class_rights[$f_cache_signature][$f_result_array['ddbgrights_id']] = new direct_right ();
						if (!$this->class_rights[$f_cache_signature][$f_result_array['ddbgrights_id']]->set ($f_result_array)) { unset ($this->class_rights[$f_cache_signature][$f_result_array['ddbgrights_id']]); }
					}
				}

				$f_return =& $this->class_rights[$f_cache_signature];
			}
		}

		return $f_return;
	}

	//f// direct_group->set_right ($f_data)
/**
	* Set a right for this group. This automatically sets the rights cache.
	*
	* @param  array $f_data Right data array
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_right ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -group_handler->set_right (+f_data)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ($this->data)
		{
			$f_cache_signature = md5 ($this->data['ddbgroups_id']."0name-asc");
			if (!isset ($this->class_rights[$f_cache_signature])) { $this->class_rights[$f_cache_signature] = array (); }

			if (isset ($f_data['ddbgrights_id']))
			{
				$this->class_rights[$f_cache_signature][$f_data['ddbgrights_id']] = new direct_right ();
				if ($this->class_rights[$f_cache_signature][$f_data['ddbgrights_id']]) { $f_return = $this->class_rights[$f_cache_signature][$f_data['ddbgrights_id']]->set ($f_data); }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->set_right ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_group->right_add ($f_rid,$f_right,$f_setup,$f_overwrite = false)
/**
	* Add a right to this group container or return false if it already exists.
	*
	* @param  string $f_rid Right database ID
	* @param  string $f_right Right name
	* @param  boolean $f_setup Granted (1 / true) or denied (0 / false)
	* @param  boolean $f_overwrite True to replace existing right definitions
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function right_add ($f_rid = "",$f_right = "",$f_setup = false,$f_overwrite = false)
	{
		global $direct_cachedata,$direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -group_handler->right_add ($f_rid,$f_right,+f_setup,+f_overwrite)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if ($this->data)
		{
			if (!$f_rid) { $f_rid = uniqid (""); }
			$f_right_object = new direct_right ();

			if ($f_right_object) { $f_right_array = $f_right_object->get_aid ($direct_settings['group_rights_table'].".ddbgrights_rid",$f_rid); }
			else { $f_right_array = NULL; }

			if ($f_right)
			{
				if ((!is_array ($f_right_array))||($f_overwrite))
				{
					$f_right_id = md5 ($f_right);

					if ($f_setup) { $f_setup = 1; }
					else { $f_setup = 0; }

$g_right_array = array (
"ddbgrights_id" => $f_rid,
"ddbgrights_name" => $f_right,
"ddbgrights_rid" => $f_right_id,
"ddbgrights_setup" => $f_setup
);

					if (is_array ($f_right_array))
					{
						$f_return = $f_right_object->set_update ($g_right_array);

						if ($f_return)
						{
							if (function_exists ("direct_dbsync_event")) { direct_dbsync_event ($direct_settings['group_rights_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_rights_table'].".ddbgrights_id",$f_rid,"string"))."</sqlconditions>")); }
							if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['group_rights_table']); }
						}
					}
					else
					{
						$f_return = $f_right_object->set_insert ($g_right_array);

						if ($f_return)
						{
							if (function_exists ("direct_dbsync_event")) { direct_dbsync_event ($direct_settings['group_rights_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_rights_table'].".ddbgrights_id",$f_rid,"string"))."</sqlconditions>")); }
							if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['group_rights_table']); }
						}
					}
				}
			}
			else { $f_return = is_array ($f_right_array); }

			if ($f_return)
			{
				$f_connect_id = md5 ($this->data['ddbgroups_id']."r".$f_rid);

				$direct_classes['db']->init_replace ($direct_settings['group_connect_table']);

$f_replace_values = ("<sqlvalues>
".($direct_classes['db']->define_values_encode ($f_connect_id,"string"))."
".($direct_classes['db']->define_values_encode ($f_gid,"string"))."
".($direct_classes['db']->define_values_encode ("r","string"))."
".($direct_classes['db']->define_values_encode ($f_rid,"string"))."
</sqlvalues>");

				$direct_classes['db']->define_values ($f_replace_values);
				$f_return = $direct_classes['db']->query_exec ("co");

				if ($f_return)
				{
					if (function_exists ("direct_dbsync_event")) { direct_dbsync_event ($direct_settings['group_connect_table'],"replace",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_connect_table'].".ddbgconnect_id",$f_connect_id,"string"))."</sqlconditions>")); }
					if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['group_connect_table']); }
				}
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->right_add ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_group->set ($f_data)
/**
	* Sets (and overwrites) the given data for this object.
	*
	* @param  array $f_data Group data array
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -group_handler->set (+f_data)- (#echo(__LINE__)#)"); }

		if (isset ($f_data['ddbgroups_id'],$f_data['ddbgroups_name']))
		{
			$f_return = true;

			if (!isset ($f_data['ddbgroups_description'])) { $f_data['ddbgroups_description'] = ""; }
			$this->data = $f_data;
		}
		else { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->set ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_group->set_insert ($f_data)
/**
	* Add the given data to this object and save them in the database.
	*
	* @param  array $f_data Group data array
	* @uses   direct_debug()
	* @uses   direct_group::set()
	* @uses   direct_group::update()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set_insert ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -group_handler->set_insert (+f_data)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = true;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->set_insert ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->set_insert ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_group->set_update ($f_data)
/**
	* Update the given data in this object and save them in the database.
	*
	* @param  array $f_data Group data array
	* @uses   direct_debug()
	* @uses   direct_group::set()
	* @uses   direct_group::update()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set_update ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -group_handler->set_update (+f_data)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = false;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->set_update ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->set_update ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_group->update ()
/**
	* Writes all object data to the database.
	*
	* @param  boolean $f_insert_mode_deactivate Deactive insert mode after calling
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_set_attributes()
	* @uses   direct_db::define_set_attributes_encode()
	* @uses   direct_db::init_insert()
	* @uses   direct_db::init_update()
	* @uses   direct_db::optimize_random()
	* @uses   direct_db::query_exec()
	* @uses   direct_dbsync_event()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function update ($f_insert_mode_deactivate = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -group_handler->update ()- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1)
		{
			if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['group_table']); }
			else { $direct_classes['db']->init_update ($direct_settings['group_table']); }

$f_data_values = ("<sqlvalues>
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_table'].".ddbgroups_id",$this->data['ddbgroups_id'],"string"))."
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_table'].".ddbgroups_name",$this->data['ddbgroups_name'],"string"))."
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_table'].".ddbgroups_description",$this->data['ddbgroups_description'],"string"))."
</sqlvalues>");

			$direct_classes['db']->define_set_attributes ($f_data_values);
			if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_table'].".ddbgroups_id",$this->data['ddbgroups_id'],"string"))."</sqlconditions>"); }
			$f_return = $direct_classes['db']->query_exec ("co");

			if ($f_return)
			{
				if (function_exists ("direct_dbsync_event"))
				{
					if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['group_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_table'].".ddbgroups_id",$this->data['ddbgroups_id'],"string"))."</sqlconditions>")); }
					else { direct_dbsync_event ($direct_settings['group_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_table'].".ddbgroups_id",$this->data['ddbgroups_id'],"string"))."</sqlconditions>")); }
				}

				if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['group_table']); }
			}
		}

		if (($f_insert_mode_deactivate)&&($this->data_insert_mode)) { $this->data_insert_mode = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -group_handler->update ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_group",true);

//j// Script specific functions

if (!isset ($direct_settings['swg_auto_maintenance'])) { $direct_settings['swg_auto_maintenance'] = false; }
}

//j// EOF
?>