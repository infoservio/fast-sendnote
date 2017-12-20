<?php

namespace infoservio\mailmanager\components\mailmanager;

use craft\events\RegisterComponentTypesEvent;
use craft\helpers\Component;
use infoservio\mailmanager\components\mailmanager\transports\BaseTransport;
use infoservio\mailmanager\components\mailmanager\transports\Mailgun;
use infoservio\mailmanager\components\mailmanager\transports\Php;
use infoservio\mailmanager\components\mailmanager\transports\Postal;
use yii\base\Event;

class MailerFactory
{
    // Constants
    // =========================================================================

    /**
     * @event RegisterComponentTypesEvent The event that is triggered when registering mailer transport adapter types.
     */
    const EVENT_REGISTER_MAILER_TRANSPORT_TYPES = 'registerMailerTransportTypes';

    // Static
    // =========================================================================

    /**
     * Returns all available mailer transport adapter classes.
     *
     * @return string[]
     */
    public static function allMailerTransportTypes(): array
    {
        $transportTypes = [
            Php::class,
            Mailgun::class,
            Postal::class
        ];

        $event = new RegisterComponentTypesEvent([
            'types' => $transportTypes
        ]);
        Event::trigger(static::class, self::EVENT_REGISTER_MAILER_TRANSPORT_TYPES, $event);

        return $event->types;
    }


    /**
     * @param string $type
     * @param array|null $settings
     * @return BaseTransport
     */
    public static function createTransport(string $type, array $settings = null): BaseTransport
    {
        /** @var BaseTransport $adapter */
        $adapter = Component::createComponent([
            'type' => $type,
            'settings' => $settings,
        ], BaseTransport::class);

        return $adapter;
    }
}