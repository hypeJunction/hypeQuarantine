<?php

return [

	'quarantine' => 'Quarantine',

	'quarantine:setting:quarantine' => 'Entity types to be quarantined pending approval',

	'quarantine:status:pending' => 'Pending Moderator Review',
	'quarantine:status:change_requested' => 'Moderator Requested Changes',
	'quarantine:status:rejected' => 'Rejected by Moderator',
	'quarantine:status:cleared' => 'Cleared by Moderator',

	'quarantine:change_status' => 'Moderate',
	'quarantine:change_status:status' => 'Status',
	'quarantine:change_status:note' => 'Add a note for the author',
	'quarantine:change_status:delete' => 'Permanently delete the item',

	'quarantine:change_status:pending' => 'Pending Review',
	'quarantine:change_status:change_requested' => 'Needs Changes',
	'quarantine:change_status:rejected' => 'Rejected',
	'quarantine:change_status:cleared' => 'Cleared',

	'quarantine:change_status:error:permissions' => 'Insufficient permissions to change the status of this item',
	'quarantine:change_status:error:unknown_status' => 'The status you are trying to set is not supported',
	'quarantine:change_status:error' => 'Status could not be changed',
	'quarantine:change_status:success' => 'Status has been successfully changed',

	'quarantine:notify:note' => "
	
	The following note was added for you:
	%s
	
	",

	'quarantine:notify:pending:subject' => 'Your post is pending moderator review',
	'quarantine:notify:pending:message' => '
		%s has flagged your post "%s" for moderator review - it will no longer be visible by other users until cleared by the moderator.
		%s
		You can view the post here:
		%s
		
		You can contact the moderator via their profile:
		%s
	',

	'quarantine:notify:change_requested:subject' => 'Moderator requested updates to your post',
	'quarantine:notify:change_requested:message' => '
		%s has requested you to make changes to your post "%s". The item will not be visible by other users until cleared by the moderator.
		%s
		You can view the post here:
		%s
		
		You can contact the moderator via their profile:
		%s
	',

	'quarantine:notify:rejected:subject' => 'Your post has been rejected',
	'quarantine:notify:rejected:message' => '
		%s rejected your post "%s".
		%s
		You can view the post here until it is deleted:
		%s
		
		You can contact the moderator via their profile:
		%s
	',

	'quarantine:notify:cleared:subject' => 'Your post has been cleared',
	'quarantine:notify:cleared:message' => '
		%s cleared your post "%s" and it is now visible by other users.
		%s
		You can view the post here:
		%s
		
		You can contact the moderator via their profile:
		%s
	',

	'roles:crud:capability:update:quarantine_status' => 'Change quarantined entity status',
];