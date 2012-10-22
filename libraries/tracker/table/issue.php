<?php
/**
 * @package     BabDev.Tracker
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2012 Michael Babker. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Table interface class for the issues table
 *
 * @package     BabDev.Tracker
 * @subpackage  Table
 * @since       1.0
 */
class JTableIssue extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  A database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__issues', 'id', $db);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/bind
	 * @since   11.1
	 * @throws  InvalidArgumentException
	 */
	public function bind($src, $ignore = array())
	{
		if (is_array($src))
		{
			return parent::bind($src, $ignore);
		}
		elseif ($src instanceof JInput)
		{
			$data = new stdClass;
			$data->id = $src->get('id');
			$fields   = $src->get('fields', array(), 'array');

			JArrayHelper::toInteger($fields);

			if (isset($fields['catid']))
			{
				$data->catid = $fields['catid'];
				unset($fields['catid']);
			}

			$this->fieldValues = $fields;

			return parent::bind($data, $ignore);
		}

		throw new InvalidArgumentException(sprintf('%s::bind(*%s*)', get_class($this), gettype($src)));
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     JTable::check
	 * @since   1.0
	 */
	public function check()
	{
		if (trim($this->title) == '')
		{
			$this->setError('A title is required.');
			return false;
		}

		if (trim($this->description) == '')
		{
			$this->setError('A description is required.');
			return false;
		}

		return true;
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();

		if ($this->id)
		{
			// Existing item
			$this->modified = $date->toSql();
		}
		else
		{
			// New item
			if (!(int) $this->opened)
			{
				$this->opened = $date->toSql();
			}
		}

		if (!parent::store($updateNulls))
		{
			throw new RuntimeException($this->getError());
		}

		if (!isset($this->fieldValues))
		{
			return true;
		}

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Check the tracker table to see if the extra fields are already present
		$query->select('fv.field_id');
		$query->from('#__tracker_fields_values AS fv');
		$query->where($db->qn('fv.issue_id') . '=' . (int) $this->id);

		$db->setQuery($query);
		$ids = $db->loadColumn();

		$queryInsert = $db->getQuery(true);
		$queryInsert->insert($this->_db->qn('#__tracker_fields_values'));
		$queryInsert->columns('issue_id, field_id, value');

		$queryUpdate = $db->getQuery(true);
		$queryUpdate->update($this->_db->qn('#__tracker_fields_values'));

		foreach ($this->fieldValues as $k => $v)
		{
			if (in_array($k, $ids))
			{
				$queryUpdate->clear('set')->clear('where');
				$queryUpdate->set($db->qn('value') . '=' . (int) $v);
				$queryUpdate->where($db->qn('issue_id') . '=' . (int) $this->id);
				$queryUpdate->where($db->qn('field_id') . '=' . (int) $k);

				// Update item
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				$queryInsert->clear('values');
				$queryInsert->values(implode(', ', array((int) $this->id, (int) $k, (int) $v)));

				// New item
				$db->setQuery($query);
				$db->execute();
			}
		}

		return true;
	}
}
