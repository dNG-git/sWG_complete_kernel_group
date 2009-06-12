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
$Id$
#echo(sWGkernelGroupVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* This module enhances the sWG Kernel with ready to use group functions.
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

if (!defined ("CLASS_direct_kernel_group"))
{
//c// direct_kernel_group
/**
* "direct_kernel_user" provides the default interface to user specific data.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage kernel
* @uses       CLASS_direct_virtual_class
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/
class direct_kernel_group extends direct_virtual_class
{
/**
	* @var array $class_groups Group data cache
*/
	protected $class_groups;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_kernel_group->__construct () and direct_kernel_group->direct_kernel_group ()
/**
	* Constructor (PHP5) __construct (direct_kernel_group)
	*
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ()
	{
		global $direct_classes;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->__construct (direct_kernel_group)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about available functions
------------------------------------------------------------------------- */

		$this->functions['group_init'] = true;
		$this->functions['group_right_check'] = false;
		$this->functions['group_right_write'] = false;
		$this->functions['group_rights_get'] = false;
		$this->functions['group_user_add_group'] = false;
		$this->functions['group_user_check_group'] = false;
		$this->functions['group_user_check_right'] = false;
		$this->functions['group_user_delete_group'] = false;
		$this->functions['group_user_get_groups'] = false;
		$this->functions['group_user_get_rights'] = false;

/* -------------------------------------------------------------------------
Set up the group initialisation code
------------------------------------------------------------------------- */

		if (!direct_class_function_check ($direct_classes['kernel'],"v_group_init"))
		{
			$direct_classes['kernel']->v_call_set ("v_group_init",$this,"group_init");
			$this->functions['group_init'] = true;
		}

		$this->class_groups = array ();
	}

	//f// direct_kernel_group->group_init ()
/**
	* Initiates the group subkernel.
	*
	* @uses   direct_basic_functions::include()
	* @uses   direct_class_init()
	* @uses   direct_db::v_connect()
	* @uses   direct_virtual_class::v_call_set()
	* @uses   USE_debug_reporting
	* @return boolean False on error
	* @since  v0.1.00
*/
	public function group_init ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_init ()- (#echo(__LINE__)#)"); }

		$f_return = true;

		if (!isset ($direct_classes['db']))
		{
			$f_return = false;
			if (($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_db.php"))&&(direct_class_init ("db"))&&($direct_classes['db']->v_connect ())) { $f_return = true; }
		}

		if ((!defined ("CLASS_direct_group"))&&(!$direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_group.php"))) { $f_return = false; }
		if ((!defined ("CLASS_direct_right"))&&(!$direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_right.php"))) { $f_return = false; }

		if ($f_return)
		{
			$direct_classes['kernel']->v_call_set ("v_group_right_check",$this,"group_right_check");
			$direct_classes['kernel']->v_call_set ("v_group_right_write",$this,"group_right_write");
			$direct_classes['kernel']->v_call_set ("v_group_rights_get",$this,"group_rights_get");
			$direct_classes['kernel']->v_call_set ("v_group_user_check_group",$this,"group_user_check_group");
			$direct_classes['kernel']->v_call_set ("v_group_user_check_right",$this,"group_user_check_right");
			$direct_classes['kernel']->v_call_set ("v_group_user_get_groups",$this,"group_user_get_groups");
			$direct_classes['kernel']->v_call_set ("v_group_user_get_rights",$this,"group_user_get_rights");
			$this->functions['group_right_check'] = true;
			$this->functions['group_right_write'] = true;
			$this->functions['group_rights_get'] = true;
			$this->functions['group_user_add_group'] = false;
			$this->functions['group_user_check_group'] = true;
			$this->functions['group_user_check_right'] = true;
			$this->functions['group_user_delete_group'] = false;
			$this->functions['group_user_get_groups'] = true;
			$this->functions['group_user_get_rights'] = true;
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_init ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_right_check ($f_gid,$f_rights,$f_explicit = false)
/**
	* Check if a group has defined rights or not.
	*
	* @param  string $f_gid Group ID
	* @param  mixed $f_rights One (string) or more (array) rights
	* @param  boolean $f_explicit True if all defined rights must be true
	* @uses   direct_basic_functions::set_debug_result()
	* @uses   direct_kernel::v_group_right_check()
	* @uses   USE_debug_reporting
	* @return boolean True if the check was successful
	* @since  v0.1.00
*/
	public function group_right_check ($f_gid,$f_rights,$f_explicit = false)
	{
		global $direct_classes;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_right_check ($f_gid,+f_rights,+f_explicit)- (#echo(__LINE__)#)"); }

		$f_return = false;

		$f_group_rights_array = $direct_classes['kernel']->v_group_rights_get ($f_gid);

		if (is_array ($f_rights)) { $f_rights_array = $f_rights; }
		else { $f_rights_array = array ($f_rights); }

		$f_explicit_check = true;

		if (!empty ($f_rights_array))
		{
			foreach ($f_rights_array as $f_right)
			{
				$f_right_id = md5 ($f_right);

				if (isset ($f_group_rights_array[$f_right_id]))
				{
					if ($f_group_rights_array[$f_right_id]) { $f_return = true; }
					elseif (($f_explicit)&&(!$f_group_rights_array[$f_right_id])) { $f_explicit_check = false; }
				}
				elseif ($f_explicit) { $f_explicit_check = false; }
			}
		}

		if (!$f_explicit_check) { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_right_check ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_right_write ($f_gid,$f_rid,$f_right,$f_setup)
/**
	* Set or delete a right for an object.
	*
	* @param  string $f_gid Group ID
	* @param  string $f_rid Right IDs
	* @param  string $f_right Right name
	* @param  boolean $f_setup True to grant the right
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_db::define_values()
	* @uses   direct_db::define_values_encode()
	* @uses   direct_db::init_delete()
	* @uses   direct_db::init_replace()
	* @uses   direct_db::query_exec()
	* @uses   direct_db::v_optimize()
	* @uses   direct_dbsync_event()
	* @uses   USE_debug_reporting
	* @return boolean True if the check was successful
	* @since  v0.1.00
*/
	public function group_right_write ($f_gid,$f_rid,$f_right,$f_setup)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_right_write ($f_gid,$f_rid,$f_right,+f_setup)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ($f_right)
		{
			$f_group_object = new direct_group ();

			if ($f_group_object)
			{
				$f_group_object->get ($f_gid,false);
				$f_return = $f_group_object->right_add ($f_rid,$f_right,$f_setup,true);
			}
		}
		elseif ($f_rid)
		{
			if ($f_gid)
			{
				$f_group_object = new direct_group ();

				if ($f_group_object)
				{
					$f_group_object->get ($f_gid,false);
					$f_return = $f_group_object->right_delete ($f_rid,$f_right);
				}
			}
			else
			{
				$f_right_object = new direct_right ();

				if ($f_right_object)
				{
					$f_right_object->get ($f_rid,false);
					$f_return = $f_right_object->delete ();
				}
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_right_write ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_rights_get ($f_gid)
/**
	* Return an array of rights for a given group ID.
	*
	* @param  string $f_gid Group ID
	* @uses   direct_db::define_attributes()
	* @uses   direct_db::define_join()
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_db::init_select()
	* @uses   direct_db::query_exec()
	* @uses   direct_group::set()
	* @uses   USE_debug_reporting
	* @return array Rights for the given group
	* @since  v0.1.00
*/
	public function group_rights_get ($f_gid)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_rights_get ($f_gid)- (#echo(__LINE__)#)"); }
		$f_return = array ();

		if (!isset ($this->class_groups[$f_gid]))
		{
			$this->class_groups[$f_gid] = new direct_group ();
			if ($this->class_groups[$f_gid]) { $this->class_groups[$f_gid]->get ($f_gid,false); }
		}

		if (isset ($this->class_groups[$f_gid]))
		{
			$f_rights_array = $this->class_groups[$f_gid]->get_rights ();

			foreach ($f_rights_array as $f_right_object)
			{
				$f_right_array = $f_right_object->get ();
				if ($f_right_array) { $f_return[$f_right_array['ddbgrights_rid']] = $f_right_array['ddbgrights_setup']; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_rights_get ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_user_check_group ($f_gid,$f_all = false)
/**
	* Check if the user is in the defined group(s).
	*
	* @param  mixed $f_gid One (string) or more (array) group ID(s)
	* @param  boolean $f_all True if the user has to be in all given groups
	* @uses   direct_basic_functions::set_debug_result()
	* @uses   direct_kernel_group::v_group_user_get_rights()
	* @uses   USE_debug_reporting
	* @return boolean True if the user is in the defined group(s)
	* @since  v0.1.00
*/
	public function group_user_check_group ($f_gid,$f_all = false)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_check_group (+f_gid,+f_all)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (isset ($direct_settings['user']['groups'])) { $f_user_groups_array = $direct_settings['user']['groups']; }
		else
		{
			$direct_classes['kernel']->v_group_user_get_rights ();
			$f_user_groups_array = $direct_settings['user']['groups'];
		}

		if (is_array ($f_gid)) { $f_groups_array = $f_gid; }
		else { $f_groups_array = array ($f_gid); }

		$f_all_check = true;

		if (!empty ($f_groups_array))
		{
			foreach ($f_groups_array as $f_gid)
			{
				if (in_array ($f_gid,$f_user_groups_array)) { $f_return = true; }
				elseif ($f_all) { $f_all_check = false; }
			}
		}

		if (!$f_all_check) { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_check_right ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_user_check_right ($f_rights,$f_explicit = false)
/**
	* Check if the user has defined rights or not.
	*
	* @param  mixed $f_rights One (string) or more (array) rights
	* @param  boolean $f_explicit True if all defined rights must be true
	* @uses   direct_basic_functions::set_debug_result()
	* @uses   direct_kernel_group::v_group_right_check()
	* @uses   USE_debug_reporting
	* @return boolean True if the check was successful
	* @since  v0.1.00
*/
	public function group_user_check_right ($f_rights,$f_explicit = false)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_check_right (+f_rights,+f_explicit)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (isset ($direct_settings['user']['rights'])) { $f_user_rights_array = $direct_settings['user']['rights']; }
		else { $f_user_rights_array = $direct_classes['kernel']->v_group_user_get_rights (); }

		if (is_array ($f_rights)) { $f_rights_array = $f_rights; }
		else { $f_rights_array = array ($f_rights); }

		$f_explicit_check = true;

		if (!empty ($f_rights_array))
		{
			foreach ($f_rights_array as $f_right)
			{
				$f_right_id = md5 ($f_right);

				if (isset ($f_group_rights_array[$f_right_id]))
				{
					if ($f_group_rights_array[$f_right_id]) { $f_return = true; }
					elseif (($f_explicit)&&(!$f_group_rights_array[$f_right_id])) { $f_explicit_check = false; }
				}
				elseif ($f_explicit) { $f_explicit_check = false; }
			}
		}

		if (!$f_explicit_check) { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_check_right ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_user_get_groups ()
/**
	* Return an array of group IDs for the current user.
	*
	* @uses   direct_db::define_attributes()
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_db::init_select()
	* @uses   direct_db::query_exec()
	* @uses   direct_kernel::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return array Group IDs for the current user
	* @since  v0.1.00
*/
	public function group_user_get_groups ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_get_groups ()- (#echo(__LINE__)#)"); }

		$f_return = array ();

		if (isset ($direct_settings['user']['groups'])) { $f_return = $direct_settings['user']['groups']; }
		else
		{
			if ($direct_settings['user']['type'] != "gt")
			{
				$direct_classes['db']->init_select ($direct_settings['group_connect_table']);
;
				$direct_classes['db']->define_attributes (array ($direct_settings['group_connect_table'].".ddbgconnect_target_id"));
				$direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['group_connect_table'].".ddbgconnect_source_id",$direct_settings['user']['id'],"string"))."<element1 attribute='ddbgconnect_target_type' value='g' type='string' /></sqlconditions>");

				$f_result_array = $direct_classes['db']->query_exec ("ms");
				if (is_array ($f_result_array)) { $f_return = $f_result_array; }
			}

			$f_return[] = $direct_settings['lang'];
			$f_return[] = $direct_settings['theme'];
			if ((isset ($direct_settings['user']['id']))&&($direct_settings['user']['id'])) { $f_return[] = $direct_settings['user']['id']; }
			$f_return[] = $direct_settings['user']['type'];
			if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 1) { $f_return[] = "me"; }

			$direct_settings['user']['groups'] = $f_return;
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_get_groups ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_kernel_group->group_user_get_rights ($f_use_cache = true)
/**
	* Return an array of rights for the current user.
	*
	* @param  boolean $f_use_cache False to reload all group rights.
	* @uses   direct_db::define_attributes()
	* @uses   direct_db::define_join()
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_db::init_select()
	* @uses   direct_db::query_exec()
	* @uses   direct_evars_get()
	* @uses   direct_evars_write()
	* @uses   direct_group::right_add()
	* @uses   direct_kernel::v_group_user_get_groups()
	* @uses   direct_kernel::v_uuid_cookie_save()
	* @uses   direct_kernel::v_uuid_get()
	* @uses   direct_kernel::v_uuid_write()
	* @uses   direct_kernel::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return array Rights for $direct_settings['user']['id']
	* @since  v0.1.00
*/
	public function group_user_get_rights ($f_use_cache = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_get_rights (+f_use_cache)- (#echo(__LINE__)#)"); }

		if (($f_use_cache)&&(isset ($direct_settings['user']['rights']))) { $f_return = $direct_settings['user']['rights']; }
		else
		{
$f_return = array (
md5 ("srlang-".$direct_settings['lang']) => 1,
md5 ("srtheme-".$direct_settings['theme']) => 1,
md5 ("srutype-".$direct_settings['user']['type']) => 1
);

			if ((isset ($direct_settings['user']['id']))&&($direct_settings['user']['id'])) { $f_return[md5 ("sruid-".$direct_settings['user']['id'])] = 1; }
			if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 1) { $f_return['11b4fcf292dd68a1e8b626412602ff10'] = 1; }
			// md5 ("srutype-me")

			if (isset ($direct_settings['user']['groups'])) { $f_groups_array = $direct_settings['user']['groups']; }
			else { $f_groups_array = $direct_classes['kernel']->v_group_user_get_groups (); }

			if (!empty ($f_groups_array))
			{
				$direct_classes['db']->init_select ($direct_settings['group_connect_table']);
				$direct_classes['db']->define_attributes (array ($direct_settings['group_connect_table'].".ddbgconnect_source_id",$direct_settings['group_rights_table'].".ddbgrights_id",$direct_settings['group_rights_table'].".ddbgrights_name",$direct_settings['group_rights_table'].".ddbgrights_rid",$direct_settings['group_rights_table'].".ddbgrights_setup"));
				$direct_classes['db']->define_join ("left-outer-join",$direct_settings['group_rights_table'],"<sqlconditions><element1 attribute='{$direct_settings['group_rights_table']}.ddbgrights_id' value='{$direct_settings['group_connect_table']}.ddbgconnect_target_id' type='attribute' /></sqlconditions>");

				$f_select_criteria = "<sqlconditions><sub1 type='sublevel'>";
				foreach ($f_groups_array as $f_gid) { $f_select_criteria .= $direct_classes['db']->define_row_conditions_encode ($direct_settings['group_connect_table'].".ddbgconnect_source_id",$f_gid,"string","==","or"); }
				$f_select_criteria .= "</sub1><element1 attribute='{$direct_settings['group_connect_table']}.ddbgconnect_target_type' value='r' type='string' /></sqlconditions>";

				$direct_classes['db']->define_row_conditions ($f_select_criteria);
				$f_results_array = $direct_classes['db']->query_exec ("ma");

				if (is_array ($f_results_array))
				{
					foreach ($f_results_array as $f_result_array)
					{
						if ((!$f_use_cache)||(!isset ($this->class_groups[$f_result_array['ddbgconnect_source_id']])))
						{
							$this->class_groups[$f_result_array['ddbgconnect_source_id']] = new direct_group ();
							if ($this->class_groups[$f_result_array['ddbgconnect_source_id']]) { $this->class_groups[$f_result_array['ddbgconnect_source_id']]->get ($f_result_array['ddbgconnect_source_id'],false); }
						}

						if ((isset ($this->class_groups[$f_result_array['ddbgconnect_source_id']]))&&($f_result_array['ddbgrights_rid']))
						{
$g_right_array = array (
"ddbgrights_id" => $f_result_array['ddbgrights_id'],
"ddbgrights_name" => $f_result_array['ddbgrights_name'],
"ddbgrights_rid" => $f_result_array['ddbgrights_rid'],
"ddbgrights_setup" => $f_result_array['ddbgrights_setup']
);

							if ((!isset ($f_return[$f_result_array['ddbgrights_rid']]))||(!$f_return[$f_result_array['ddbgrights_rid']])) { $f_return[$f_result_array['ddbgrights_rid']] = $f_result_array['ddbgrights_setup']; }
							$this->class_groups[$f_result_array['ddbgconnect_source_id']]->set_right ($g_right_array);
						}
					}
				}
			}

			$direct_settings['user']['rights'] = $f_return;

			$f_uuid_string = $direct_classes['kernel']->v_uuid_get ("s");

			$f_uuid_array = direct_evars_get ($f_uuid_string);
			$f_uuid_array['groups'] = $direct_settings['user']['groups'];
			$f_uuid_array['rights'] = $direct_settings['user']['rights'];
			$f_uuid_string = direct_evars_write ($f_uuid_array);

			$direct_classes['kernel']->v_uuid_write ($f_uuid_string);
			$direct_classes['kernel']->v_uuid_cookie_save ();
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -subkernel_group->group_user_get_rights ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

$direct_classes['@names']['kernel_group'] = "direct_kernel_group";
define ("CLASS_direct_kernel_group",true);

//j// Script specific functions

direct_class_init ("kernel_group");
}

//j// EOF
?>