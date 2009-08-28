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

$g_continue_check = ((defined ("CLASS_direct_right")) ? false : true);
if (!defined ("CLASS_direct_data_handler")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_right
/**
* This abstraction layer provides right specific functions.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage kernel
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/
class direct_right extends direct_data_handler
{
/**
	* @var boolean $data_subs_allowed True if the next "dclass_update ()" call
	*      is an insert - the code is the same.
*/
	protected $data_insert_mode;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_right->__construct ()
/**
	* Constructor (PHP5) __construct (direct_right)
	*
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -right_handler->__construct (direct_right)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about available functions 
------------------------------------------------------------------------- */

//		$this->functions['delete'] = true;
		$this->functions['get_aid'] = true;
		$this->functions['set_insert'] = true;
		$this->functions['set_update'] = true;
		$this->functions['update'] = true;

/* -------------------------------------------------------------------------
Set up the cache
------------------------------------------------------------------------- */

		$this->data = array ();
		$this->data_insert_mode = false;
	}

	//f// direct_right->get ($f_rid = "",$f_load = true)
/**
	* Reads in a right entry with the specified database ID.
	*
	* @param  string $f_rid Right database ID
	* @param  boolean $f_load Load right data from the database
	* @uses   direct_debug()
	* @uses   direct_right::get_aid()
	* @uses   USE_debug_reporting
	* @return mixed Right array; false on error
	* @since  v0.1.00
*/
	public function get ($f_rid = "",$f_load = true)
	{
		if (USE_debug_reporting) { direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->get ($f_rid,+f_load)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ($f_load) { $f_return = $this->get_aid (NULL,$f_rid); }
		elseif (strlen ($f_rid))
		{
			$this->data = array ("ddbgrights_id" => $f_rid);
			$f_return = $this->data;
		}

		return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -right_handler->get ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
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
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -right_handler->get_aid (+f_attributes,+f_values)- (#echo(__LINE__)#)"); }

		if (!isset ($f_attributes)) { $f_attributes = $direct_settings['group_rights_table'].".ddbgrights_id"; }
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
				$direct_classes['db']->init_select ($direct_settings['group_rights_table']);

				$direct_classes['db']->define_attributes ($direct_settings['group_rights_table'].".*");

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

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->get_aid ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_right->set ($f_data)
/**
	* Add a right to this group container or return false if it already exists.
	*
	* @param  array $f_data Right data array
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -right_handler->set (+f_data)- (#echo(__LINE__)#)"); }

		if (isset ($f_data['ddbgrights_id'],$f_data['ddbgrights_rid']))
		{
			if (!isset ($f_data['ddbgrights_name'])) { $f_data['ddbgrights_name'] = $f_data['ddbgrights_rid']; }
			if (!isset ($f_data['ddbgrights_setup'])) { $f_data['ddbgrights_setup'] = 0; }

			$this->data = $f_data;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->set ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->set ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_right->set_insert ($f_data)
/**
	* Add the given data to this object and save them in the database.
	*
	* @param  array $f_data Right data array
	* @uses   direct_debug()
	* @uses   direct_right::set()
	* @uses   direct_right::update()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set_insert ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -right_handler->set_insert (+f_data)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = true;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->set_insert ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->set_insert ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_right->set_update ($f_data)
/**
	* Update the given data in this object and save them in the database.
	*
	* @param  array $f_data Group data array
	* @uses   direct_debug()
	* @uses   direct_right::set()
	* @uses   direct_right::update()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set_update ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -right_handler->set_update (+f_data)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = false;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->set_update ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->set_update ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_right->update ()
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
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -right_handler->update ()- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1)
		{
			if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['group_rights_table']); }
			else { $direct_classes['db']->init_update ($direct_settings['group_rights_table']); }

$f_data_values = ("<sqlvalues>
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_rights_table'].".ddbgrights_id",$this->data['ddbgrights_id'],"string"))."
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_rights_table'].".ddbgrights_name",$this->data['ddbgrights_name'],"string"))."
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_rights_table'].".ddbgrights_rid",$this->data['ddbgrights_rid'],"string"))."
".($direct_classes['db']->define_set_attributes_encode ($direct_settings['group_rights_table'].".ddbgrights_setup",$this->data['ddbgrights_setup'],"string"))."
</sqlvalues>");

			$direct_classes['db']->define_set_attributes ($f_data_values);
			if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_rights_table'].".ddbgrights_id",$this->data['ddbgrights_id'],"string"))."</sqlconditions>"); }
			$f_return = $direct_classes['db']->query_exec ("co");

			if ($f_return)
			{
				if (function_exists ("direct_dbsync_event"))
				{
					if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['group_rights_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_rights_table'].".ddbgrights_id",$this->data['ddbgrights_id'],"string"))."</sqlconditions>")); }
					else { direct_dbsync_event ($direct_settings['group_rights_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_rights_table'].".ddbgrights_id",$this->data['ddbgrights_id'],"string"))."</sqlconditions>")); }
				}

				if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['group_rights_table']); }
			}
		}

		if (($f_insert_mode_deactivate)&&($this->data_insert_mode)) { $this->data_insert_mode = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -right_handler->update ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_right",true);

//j// Script specific functions

if (!isset ($direct_settings['swg_auto_maintenance'])) { $direct_settings['swg_auto_maintenance'] = false; }
}

//j// EOF
?>