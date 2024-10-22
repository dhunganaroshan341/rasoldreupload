<?php

namespace Abivia\Ledger\Messages;

use Abivia\Ledger\Exceptions\Breaker;
use Abivia\Ledger\Models\LedgerAccount;
use Abivia\Ledger\Models\LedgerName;
use Illuminate\Database\Eloquent\Model;

class Name extends Message
{
    protected static array $copyable = [
        ['exclude', Message::OP_QUERY],
        'language',
        ['like', Message::OP_QUERY],
    ];

    /**
     * @var bool The Name/language should be excluded from results. Used for queries.
     */
    public bool $exclude = false;

    /**
     * @var string The language this name is in.
     */
    public string $language;

    /**
     * @var bool The Name/language are in SQL "like" form. Used for queries.
     */
    public bool $like = false;

    /**
     * @var string The value of this name.
     */
    public string $name;

    /**
     * @var string The entity this name is attached to.
     */
    public string $ownerUuid;

    public function __construct(
        ?string $name = null,
        ?string $language = null,
        ?string $ownerUuid = null
    ) {
        if ($language !== null) {
            $this->language = $language;
        }
        if ($name !== null) {
            $this->name = $name;
        }
        if ($ownerUuid !== null) {
            $this->ownerUuid = $ownerUuid;
        }
    }

    /**
     * Add, update, or delete this name from/to a model
     * @param Model $owner
     * @return void
     */
    public function applyTo(Model $owner)
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $ledgerName = $owner->names->firstWhere('language', $this->language);
        if ($this->name === '') {
            if ($ledgerName !== null) {
                $ledgerName->delete();
            }
        } else {
            if ($ledgerName === null) {
                $ledgerName = new LedgerName();
                $ledgerName->ownerUuid = $owner->getKey();
                $ledgerName->language = $this->language;
            }
            $ledgerName->name = $this->name;
            $ledgerName->save();
        }
    }

    /**
     * @inheritdoc
     */
    public static function fromArray(array $data, int $opFlags = self::OP_ADD): self
    {
        $name = new static();
        $name->copy($data, $opFlags);
        $name->name = $data['name'] ?? '';
        if ($opFlags & self::F_VALIDATE) {
            $name->validate($opFlags);
        }

        return $name;
    }

    /**
     * Populate an array of names with request data.
     *
     * @param array $data Data generated by the request.
     * @param int $opFlags Bitmask of the request operation (may include FM_VALIDATE)
     * @param int $minimum the minimum number of elements that should be present.
     * @return Name[]
     * @throws Breaker
     */
    public static function fromRequestList(array $data, int $opFlags, int $minimum = 0): array
    {
        $names = [];
        foreach ($data as $nameData) {
            $message = self::fromArray($nameData, $opFlags);
            if ($opFlags & Message::OP_QUERY) {
                $names[] = $message;
            } else {
                $names[$message->language ?? ''] = $message;
            }
        }
        if (count($names) < $minimum) {
            $entry = $minimum === 1 ? 'entry' : 'entries';
            throw Breaker::withCode(
                Breaker::BAD_REQUEST, ["must provide at least $minimum name $entry"]
            );
        }

        return $names;
    }

    /**
     * @inheritdoc
     */
    public function validate(?int $opFlags = null): self
    {
        $opFlags ??= $this->getOpFlags();
        // The name is not required for update or query operations.
        if ($this->name === '' && !($opFlags & (self::OP_QUERY | self::OP_UPDATE))) {
            throw Breaker::withCode(
                Breaker::RULE_VIOLATION, [__("Must include name property.")]
            );
        }
        $rules = LedgerAccount::rules(
            bootable: $opFlags & self::OP_CREATE
        );
        // The language can be empty on a query
        if ($opFlags & Message::OP_QUERY) {
            $this->language ??= '';
        } else {
            $this->language ??= $rules->language->default;
            if ($this->language === '') {
                throw Breaker::withCode(
                    Breaker::RULE_VIOLATION, [__("Language cannot be empty.")]
                );
            }
        }

        return $this;
    }
}