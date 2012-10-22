<?php
/**
 * @package    BabDev.Tracker
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Initialize values to check for cells
$blockers = array('1', '2');

// Initialize Bootstrap Tooltips
$ttParams = array();
$ttParams['animation'] = true;
$ttParams['trigger']   = 'hover';
JHtml::_('bootstrap.tooltip', '.hasTooltip', $ttParams);
JHtml::_('formbehavior.chosen', 'select');

$filterStatus = $this->state->get('filter.status');

$fields = new JRegistry(JFactory::getApplication()->input->get('fields', array(), 'array'));

$uri = JURI::getInstance();

$quickFilters = array(
	'COM_TRACKER_QUICK_FILTER_PRIORITY' => array(
		array('name' => 'COM_TRACKER_QUICK_FILTER_PRIORITY_ALL', 'key' => 'priority', 'value' => 0),
		array('name' => 'COM_TRACKER_QUICK_FILTER_PRIORITY_HIGH' , 'key' => 'priority', 'value' => 1),
		array('name' => 'COM_TRACKER_QUICK_FILTER_PRIORITY_MEDIUM_HIGH' , 'key' => 'priority', 'value' => 2),
		array('name' => 'COM_TRACKER_QUICK_FILTER_PRIORITY_MEDIUM' , 'key' => 'priority', 'value' => 3),
		array('name' => 'COM_TRACKER_QUICK_FILTER_PRIORITY_LOW' , 'key' => 'priority', 'value' => 4),
		array('name' => 'COM_TRACKER_QUICK_FILTER_PRIORITY_VERY_LOW' , 'key' => 'priority', 'value' => 5),
	),
	'COM_TRACKER_QUICK_FILTER_STATUS' => array(
		array('name' => 'COM_TRACKER_QUICK_FILTER_STATUS_ALL' , 'key' => 'status', 'value' => 0),
		array('name' => 'COM_TRACKER_QUICK_FILTER_STATUS_OPEN' , 'key' => 'status', 'value' => 1),
		array('name' => 'COM_TRACKER_QUICK_FILTER_STATUS_CONFIRMED' , 'key' => 'status', 'value' => 2),
		array('name' => 'COM_TRACKER_QUICK_FILTER_STATUS_PENDING' , 'key' => 'status', 'value' => 3),
		array('name' => 'COM_TRACKER_QUICK_FILTER_STATUS_RTC' , 'key' => 'status', 'value' => 4),
		array('name' => 'COM_TRACKER_QUICK_FILTER_STATUS_FIXED' , 'key' => 'status', 'value' => 5),
	),
	'COM_TRACKER_QUICK_FILTER_OPENDATE' => array(
		array('name' => 'COM_TRACKER_QUICK_FILTER_OPENDATE_ALL' , 'key' => 'opendate', 'value' => 'all'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_OPENDATE_TODAY' , 'key' => 'opendate', 'value' => 'today'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_OPENDATE_THISWEEK' , 'key' => 'opendate', 'value' => 'thisweek'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_OPENDATE_THISMONTH' , 'key' => 'opendate', 'value' => 'thismonth'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_OPENDATE_LAST3' , 'key' => 'opendate', 'value' => 'last3'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_OPENDATE_LAST6' , 'key' => 'opendate', 'value' => 'last6'),
	),
	'COM_TRACKER_QUICK_FILTER_THAT' => array(
		array('name' => 'COM_TRACKER_QUICK_FILTER_THAT_I_CREATED' , 'key' => 'that', 'value' => 'icreated'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_THAT_I_COMMENTED' , 'key' => 'that', 'value' => 'icommented'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_THAT_NO_COMMENTS' , 'key' => 'that', 'value' => 'havenocomment'),
		array('name' => 'COM_TRACKER_QUICK_FILTER_THAT_RESET' , 'key' => 'that', 'value' => 'nothing')
	)
);

?>

<fieldset class="quick-filters">
    <legend>Quick filters</legend>

	<?php foreach ($quickFilters as $filterName => $filterItems) : ?>
		<dl>
			<dt><?php echo JText::_($filterName) ?></dt>
			<?php
			foreach ($filterItems as $item) :
				$oldVar = $uri->getVar($item['key']);
				$uri->setVar($item['key'], $item['value']);
			?>
            <dd><a href="<?php echo JRoute::_($uri->__toString())?>"><?php echo JText::_($item['name']) ?></a></dd>
			<?php
				$uri->setVar($item['key'], $oldVar);
			endforeach;
			?>
		</dl>
	<?php endforeach; ?>
</fieldset>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<div class="filters btn-toolbar clearfix">
		<div class="filter-search btn-group pull-left input-append">
			<label class="filter-search-lbl element-invisible" for="filter-search"><?php echo JText::_('COM_TRACKER_FILTER_SEARCH_DESCRIPTION'); ?></label>
			<input type="text" class="input-xlarge" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TRACKER_FILTER_SEARCH_DESCRIPTION'); ?>" placeholder="<?php echo JText::_('COM_TRACKER_FILTER_SEARCH_DESCRIPTION'); ?>" />
			<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button class="btn tip hasTooltip" type="button" onclick="jQuery('#filter-search').val('');document.adminForm.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
		</div>
		<div class="btn-group pull-left">
			<?php echo JHtmlProjects::select('com_tracker', 'project', (int) $fields->get('project'), JText::_('Filter by Project')); ?>
		</div>
		<div class="btn-group pull-right">
			<label for="status" class="element-invisible"><?php echo JText::_('COM_TRACKER_FILTER_STATUS'); ?></label>
			<select name="status" id="filter-status" class="input-medium" onchange="document.adminForm.submit();">
				<option value=""><?php echo JText::_('COM_TRACKER_FILTER_STATUS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('status.options'), 'value', 'text', $filterStatus);?>
			</select>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
	</div>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th width="2%" class="nowrap hidden-phone"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
				<th><?php echo JText::_('COM_TRACKER_HEADING_SUMMARY'); ?></th>
				<th width="5%"><?php echo JText::_('COM_TRACKER_HEADING_PRIORITY'); ?></th>
				<th width="10%"><?php echo JText::_('JSTATUS'); ?></th>
				<th width="10%" class="hidden-phone"><?php echo JText::_('JCATEGORY'); ?></th>
				<th width="10%" class="hidden-phone"><?php echo JText::_('COM_TRACKER_HEADING_DATE_OPENED'); ?></th>
				<th width="10%" class="hidden-phone"><?php echo JText::_('COM_TRACKER_HEADING_DATE_CLOSED'); ?></th>
				<th width="10%" class="hidden-phone"><?php echo JText::_('COM_TRACKER_HEADING_LAST_MODIFIED'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if (count($this->items) == 0) : ?>
			<tr>
				<td class="center" colspan="8">
					<?php echo JText::_('COM_TRACKER_NO_ITEMS_FOUND'); ?>
				</td>
			</tr>
		<?php else : ?>
		<?php foreach ($this->items as $i => $item) :
		$rowClass = '';
		if (in_array($item->priority, $blockers)) {
			$rowClass = 'class="error"';
		}
		if ($item->status == '4') {
			$rowClass = 'class="success"';
		}
		?>
			<tr <?php echo $rowClass; ?>>
				<td class="center hidden-phone">
					<?php echo (int) $item->id; ?>
				</td>
				<td class="hasContext">
					<div class="hasTooltip" title="<?php echo JHtml::_('string.truncate', $item->description, 100); ?>">
						<a href="index.php?option=com_tracker&view=issue&id=<?php echo (int) $item->id;?>">
						<?php echo $this->escape($item->title); ?></a>
					</div>
					<?php if ($item->gh_id || $item->jc_id) : ?>
					<div class="small">
						<?php if ($item->gh_id) : ?>
						<?php echo JText::_('COM_TRACKER_HEADING_GITHUB_ID'); ?>
						<a href="https://github.com/joomla/joomla-cms/issues/<?php echo (int) $item->gh_id; ?>" target="_blank">
							<?php echo (int) $item->gh_id; ?>
						</a>
						<?php endif; ?>
						<?php if ($item->gh_id && $item->jc_id) echo '<br />'; ?>
						<?php if ($item->jc_id) : ?>
						<?php echo JText::_('COM_TRACKER_HEADING_JOOMLACODE_ID'); ?>
						<a href="http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=<?php echo (int) $item->jc_id; ?>" target="_blank">
							<?php echo (int) $item->jc_id; ?>
						</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php if($item->priority == 1)
					{
						$status_class = 'badge-important';
						$priority_title = JText::_('COM_TRACKER_PRIORITY_HIGH');
					}
					elseif ($item->priority == 2)
					{
						$status_class = 'badge-warning';
						$priority_title = JText::_('COM_TRACKER_PRIORITY_MEDIUM_HIGH');
					}
					elseif ($item->priority == 3)
					{
						$status_class = 'badge-info';
						$priority_title = JText::_('COM_TRACKER_PRIORITY_MEDIUM');
					}
					elseif ($item->priority == 4)
					{
						$status_class = 'badge-inverse';
						$priority_title = JText::_('COM_TRACKER_PRIORITY_LOW');
					}
					elseif ($item->priority == 5)
					{
						$status_class = '';
						$priority_title = JText::_('COM_TRACKER_PRIORITY_VERY_LOW');
					}
					?>
					<span class="badge <?php echo $status_class; ?>">
						<?php echo $priority_title; ?>
					</span>
				</td>
				<td>
					<?php echo JText::_('COM_TRACKER_STATUS_' . strtoupper($item->status_title)); ?>
				</td>
				<td class="hidden-phone">
					<?php echo $item->category ? : 'N/A'; ?>
				</td>
				<td class="nowrap small hidden-phone">
					<?php echo JHtml::_('date', $item->opened, 'DATE_FORMAT_LC4'); ?>
				</td>
				<td class="nowrap small hidden-phone">
					<?php if ($item->closed_status) : ?>
						<?php echo JHtml::_('date', $item->closed_date, 'DATE_FORMAT_LC4'); ?>
					<?php endif; ?>
				</td>
				<td class="nowrap small hidden-phone">
					<?php if ($item->modified != '0000-00-00 00:00:00') : ?>
						<?php echo JHtml::_('date', $item->modified, 'DATE_FORMAT_LC4'); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->pagination->getListFooter(); ?>
	<input type="hidden" name="task" />
</form>
