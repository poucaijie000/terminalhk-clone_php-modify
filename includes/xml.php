<?php
/**
 * Group: [Group Number]
 * Members: [Member Name] ([Student ID]), [Member Name] ([Student ID])
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * XML data access layer. Every page uses these functions instead of reading
 * or writing XML directly, so the data rules stay in one place.
 *
 * Data files:
 * - data/users.xml : registered users, password hashes and reset codes.
 * - data/items.xml : product catalogue used by the item list/detail pages.
 */

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

const DATA_DIR = __DIR__ . '/../data';
const USERS_XML = DATA_DIR . '/users.xml';
const ITEMS_XML = DATA_DIR . '/items.xml';

/**
 * Load an XML file as a SimpleXML object.
 *
 * @param string $path Absolute path to the XML file.
 * @return SimpleXMLElement|null XML root, or null when the file is missing/invalid.
 */
function load_xml(string $path): ?SimpleXMLElement
{
    if (!is_file($path) || !is_readable($path)) {
        return null;
    }

    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($path, 'SimpleXMLElement', LIBXML_NONET);
    libxml_clear_errors();

    return $xml === false ? null : $xml;
}

/**
 * Save an XML object back to disk with an exclusive lock.
 *
 * @param SimpleXMLElement $xml  XML root to save.
 * @param string           $path Destination file.
 * @return bool True when the file was written.
 */
function save_xml(SimpleXMLElement $xml, string $path): bool
{
    $document = new DOMDocument('1.0', 'UTF-8');
    $document->preserveWhiteSpace = false;
    $document->formatOutput = true;

    if (!$document->loadXML($xml->asXML())) {
        return false;
    }

    if (!is_dir(dirname($path))) {
        return false;
    }

    $result = file_put_contents($path, $document->saveXML(), LOCK_EX);
    if ($result === false) {
        return false;
    }

    @chmod($path, 0664);

    return true;
}

/**
 * Return the next numeric id for a repeated XML node.
 *
 * @param SimpleXMLElement $root Parent node, for example <users>.
 * @param string           $node Child node name, for example "user".
 * @return int Next unused positive id.
 */
function next_numeric_id(SimpleXMLElement $root, string $node): int
{
    $max = 0;
    foreach ($root->{$node} as $entry) {
        $max = max($max, (int) $entry->id);
    }

    return $max + 1;
}

/**
 * Find a registered user by email address.
 *
 * @param SimpleXMLElement $users Loaded users.xml root.
 * @param string           $email Normalised email address.
 * @return SimpleXMLElement|null Matching user node.
 */
function find_user_by_email(SimpleXMLElement $users, string $email): ?SimpleXMLElement
{
    foreach ($users->user as $user) {
        if (strcasecmp((string) $user->email, $email) === 0) {
            return $user;
        }
    }

    return null;
}

/**
 * Find a catalogue item by its public id.
 *
 * @param SimpleXMLElement $items Loaded items.xml root.
 * @param string           $id    Item id from the URL.
 * @return SimpleXMLElement|null Matching item node.
 */
function find_item_by_id(SimpleXMLElement $items, string $id): ?SimpleXMLElement
{
    foreach ($items->item as $item) {
        if ((string) $item->id === $id) {
            return $item;
        }
    }

    return null;
}

/**
 * Return all catalogue items sorted by the "featured" flag and name.
 *
 * @param SimpleXMLElement $items Loaded items.xml root.
 * @return SimpleXMLElement[] Item nodes.
 */
function all_items(SimpleXMLElement $items): array
{
    $list = [];
    foreach ($items->item as $item) {
        $list[] = $item;
    }

    usort($list, static function (SimpleXMLElement $a, SimpleXMLElement $b): int {
        $featured = (int) $b->featured <=> (int) $a->featured;
        if ($featured !== 0) {
            return $featured;
        }

        return strcmp((string) $a->name, (string) $b->name);
    });

    return $list;
}

/**
 * Format a price stored in XML as a Hong Kong dollar string.
 *
 * @param mixed $price Numeric price value.
 * @return string Price such as $3,799.
 */
function format_price($price): string
{
    return '$' . number_format((float) $price, 0, '.', ',');
}

/**
 * Validate a product id used in a query string.
 *
 * @param string $id Raw id from the URL.
 * @return bool True for lower-case letters, digits and hyphens only.
 */
function is_valid_item_id(string $id): bool
{
    return preg_match('/^[a-z0-9-]{1,40}$/', $id) === 1;
}
