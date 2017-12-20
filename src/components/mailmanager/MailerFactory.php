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
    const TRANSPORT_TYPES_CODES = [
        Php::class => ['id' => 1, 'name' => 'Php Mailer'],
        Mailgun::class => ['id' => 2, 'name' => 'Mailgun Mailer'],
        Postal::class => ['id' => 3, 'name' => 'Postal Mailer']
    ];

    const TRANSPORT_TYPES = [
        Php::class,
        Mailgun::class,
        Postal::class
    ];
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
        $event = new RegisterComponentTypesEvent([
            'types' => self::TRANSPORT_TYPES
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