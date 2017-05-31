# hypeQuarantine for Elgg

![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

## Features

* Implements a generic extentable framework for content moderation
* Uses a different approach that does not interfere with entity access_id
* Integrates with ArckInteractive's roles framework


## Considerations

### Privacy

Users that are granted moderator permissions via the roles CRUD interface will have access
to all entities of a given type regardless of their access level.

### Notifications

Most plugins register their notification handlers for the `create` event, which in case
of quarantined entities will not notify any of the subscribers due to limited access. 
If you require subscription notifications, register your handlers for `quarantine_status_cleared`
 event, but note that the event actor in this case is not the owner of the entity, but the
 moderator, so update your notification handlers accordingly (i.e. load the entity owner,
 instead of access the event actor);
 