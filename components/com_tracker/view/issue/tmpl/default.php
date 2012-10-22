<?php
/**
 * @package    BabDev.Tracker
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Get the additional fields
$browser   = $this->fields->get('browser');
$database  = $this->fields->get('database');
$php       = $this->fields->get('php_version');
$webserver = $this->fields->get('web_server');
?>

<div class="container-fluid">
    <h3><?php echo '[#' . $this->item->id . '] - ' . $this->item->title; ?></h3>

    <div class="row-fluid">
		<div class="span9">
			<h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_INFO'); ?></h4>
			<table class="table">
				<tr class="issue-info-row">
					<td>
						<label><?php echo JText::_('JSTATUS'); ?></label>
						<?php echo JText::_('COM_TRACKER_STATUS_' . strtoupper($this->item->status_title)); ?>
					</td>
					<td>
	    	    	    <label><?php echo JText::_('COM_TRACKER_HEADING_PRIORITY'); ?></label>
						<?php if($this->item->priority == 1)
						{
							$status_class = 'badge-important';
							$priority_title = JText::_('COM_TRACKER_PRIORITY_HIGH');
						}
						elseif ($this->item->priority == 2)
						{
							$status_class = 'badge-warning';
							$priority_title = JText::_('COM_TRACKER_PRIORITY_MEDIUM_HIGH');
						}
						elseif ($this->item->priority == 3)
						{
							$status_class = 'badge-info';
							$priority_title = JText::_('COM_TRACKER_PRIORITY_MEDIUM');
						}
						elseif ($this->item->priority == 4)
						{
							$status_class = 'badge-inverse';
							$priority_title = JText::_('COM_TRACKER_PRIORITY_LOW');
						}
						elseif ($this->item->priority == 5)
						{
							$status_class = '';
							$priority_title = JText::_('COM_TRACKER_PRIORITY_VERY_LOW');
						}
						?>
	    	    	    <span class="badge <?php echo $status_class; ?>">
							<?php echo $priority_title; ?>
						</span>
	    	    	</td>
				</tr>
				<tr class="issue-info-row">
					<td>
						<label><?php echo JText::_('COM_TRACKER_HEADING_GITHUB_ID'); ?></label>
						<?php if ($this->item->gh_id) : ?>
						<a href="https://github.com/joomla/joomla-cms/issues/<?php echo $this->item->gh_id; ?>" target="_blank"><?php echo $this->item->gh_id; ?></a>
						<?php endif; ?>
					</td>
					<td>
						<label><?php echo JText::_('COM_TRACKER_HEADING_JOOMLACODE_ID'); ?></strong></label>
						<?php if ($this->item->jc_id) : ?>
                        <a href="http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=<?php echo (int) $this->item->jc_id; ?>" target="_blank">
							<?php echo (int) $this->item->jc_id; ?>
                        </a>
						<?php endif; ?>
					</td>
				</tr>
				<tr class="issue-info-row">
					<td>
						<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_PATCH_URL'); ?></label>
						<?php if ($this->item->patch_url) : ?>
						<a href="<?php echo $this->item->patch_url; ?>" target="_blank"><?php echo $this->item->patch_url; ?></a>
						<?php endif; ?>
					</td>
					<td>
						<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_DATABASE_TYPE') ?></label>
						<?php if ($database) : ?>
                        	<?php echo $this->item->database_type; ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr class="issue-info-row">
					<td>
						<label><?php echo JText::_('COM_TRACKER_HEADING_DATE_OPENED'); ?></label>
                    	<?php echo JHtml::_('date', $this->item->opened, 'DATE_FORMAT_LC2'); ?>
					</td>
                    <td>
                        <label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_WEBSERVER') ?></label>
						<?php if ($webserver) : ?>
                        	<?php echo $this->item->webserver; ?>
						<?php endif; ?>
                    </td>
				</tr>
				<tr class="issue-info-row">
					<td>
						<label><?php echo JText::_('COM_TRACKER_HEADING_DATE_CLOSED'); ?></label>
						<?php if ($this->item->closed) : ?>
							<?php echo JHtml::_('date', $this->item->closed_date, 'DATE_FORMAT_LC2'); ?>
						<?php endif; ?>
					</td>
					<td>
						<label><?php echo JText::_('COM_TRACKER_HEADING_PHP_VERSION'); ?></label>
						<?php if ($php) : ?>
							<?php echo $this->item->php_version; ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr class="issue-info-row">
					<td>
						<label><?php echo JText::_('COM_TRACKER_HEADING_LAST_MODIFIED'); ?></label>
						<?php if ($this->item->modified != '0000-00-00 00:00:00') : ?>
							<?php echo JHtml::_('date', $this->item->modified, 'DATE_FORMAT_LC2'); ?>
						<?php endif; ?>
					</td>
					<td>
						<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_DATABASE_TYPE'); ?></label>
						<?php if ($database) : ?>
							<?php echo $database; ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr class="issue-info-row">
                    <td>
						<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_BROWSER'); ?></label>
						<?php if ($this->item->browser) : ?>
							<?php echo $this->item->browser; ?>
						<?php endif; ?>
					</td>
					<td>
						<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_SUCCESSFUL_TEST_COUNT') ?></label>
					</td>
				</tr>
			</table>
		</div>
		<div class="span3 pull-right">
            <h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_INVOLVED_PEOPLE'); ?></h4>
        	<ul class="involved">
				<?php foreach ($this->involvedPeople as $people) : ?>
				<li>
					<img src="<?php echo $people->avatar_url ?>" alt="<?php echo $people->submitter ?>" width="50" height="50"/>
					<a href="https://github.com/<?php echo $people->submitter ?>"><?php echo $people->submitter ?></a>
				</li>
				<?php endforeach ?>
            </ul>
		</div>
	</div>

	<div class="row-fluid">
        <div class="span12">
            <h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_DESC'); ?></h4>
            <div class="well well-small issue">
                <p><?php echo $this->item->description; ?></p>
            </div>
        </div>
	</div>

    <div class="row-fluid">
        <div class="span12">
            <h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_TEST_INSTRUCTIONS'); ?></h4>
            <div class="well well-small issue">
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
					Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
					Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
					Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
					In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.
					Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.
					Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.
					Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.
					Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.
					Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum.
					Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.
				</p>
            </div>
        </div>
    </div>

	<?php if ($this->comments) : ?>
	<div class="row-fluid">
		<div class="span12">
			<h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_COMMENTS'); ?></h4>
		</div>
	</div>
	<?php foreach ($this->comments as $comment) : ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="well well-small">
				<h5><?php echo JText::sprintf('COM_TRACKER_LABEL_SUBMITTED_BY', $comment->submitter, $comment->created); ?></h5>
				<p><?php echo $comment->text; ?></p>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
</div>
