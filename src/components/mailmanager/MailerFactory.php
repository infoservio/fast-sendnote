<?php


namespace endurant\mailmanager\components\mailmanager;

use craft\errors\MissingComponentException;
use craft\events\RegisterComponentTypesEvent;
use craft\helpers\Component;
use endurant\mailmanager\components\mailmanager\transportadapters\BaseTransportAdapter;
use endurant\mailmanager\components\mailmanager\transportadapters\MailgunMailer;
use endurant\mailmanager\components\mailmanager\transportadapters\PhpMailer;
use endurant\mailmanager\components\mailmanager\transportadapters\TransportAdapterInterface;
use Mailgun\Tests\Mock\Mailgun;
use yii\base\Event;

class MailerFactory extends MailerFactoryAbstract
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
            PhpMailer::class,
            MailgunMailer::class
        ];

        $event = new RegisterComponentTypesEvent([
            'types' => $transportTypes
        ]);
        Event::trigger(static::class, self::EVENT_REGISTER_MAILER_TRANSPORT_TYPES, $event);

        return $event->types;
    }

    /**
     * Creates a transport adapter based on the given mail settings.
     *
     * @param string     $type
     * @param array|null $settings
     *
     * @return TransportAdapterInterface
     * @throws MissingComponentException if $type is missing
     */
    public static function createTransportAdapter(string $type, array $settings = null): TransportAdapterInterface
    {
        /** @var BaseTransportAdapter $adapter */
        $adapter = Component::createComponent([
            'type' => $type,
            'settings' => $settings,
        ], TransportAdapterInterface::class);

        return $adapter;
    }
}