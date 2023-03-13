<?php

namespace Snap\Database\Model\Traits;

use Carbon\Carbon;
use Snap\Database\Model\Archive;

trait IsRestorable
{
    public static $DEFAULT_ARCHIVE_MAX = 10;

    private $_versions;

    public function archiveMaxVersion()
    {
        return defined('static::ARCHIVE_MAX') ? static::ARCHIVE_MAX : static::$DEFAULT_ARCHIVE_MAX;
    }

    public function archive()
    {
        $archive = $this->getArchiveModel();
        $archive->ref_id = $this->getKey();
        $archive->entity = $this->getArchiveEntityString();
        $archive->data = $this->getArchivableData();
        $archive->version = $this->nextArchiveVersion();

        $last = $this->getLastArchive();

        $saved = false;

        // Only save if the data is different from the last one.
        if (! $last || $last->data != $archive->data) {
            if ($saved = $archive->save()) {
                $this->truncateArchive();
            }
        }

        unset($this->_versions);

        return $saved;
    }

    public function restoreVersion($version = null)
    {
        $data = $this->getRestoreData($version);

        if ($data) {
            $this->fill($data);
            return $this->save();
        }

        return false;
    }

    public function getRestoreData($version = null)
    {
        if ($version) {
            $archive = $this->version($version);
        } else {
            $archive = $this->versions()->first();
        }

        if ($archive) {
            return $archive->data;
        }

        return null;
    }

    public function truncateArchive()
    {
        $versions = $this->versions();

        if ($versions->count() > $this->archiveMaxVersion()) {
            $offset = $this->archiveMaxVersion();
            $length = count($versions) - $offset + 1;
            foreach ($versions->slice($offset, $length) as $entity) {
                $entity->delete();
            }
        }
    }

    public function getArchivableData()
    {
        $data = [];
        $keys = $this->getArchivableKeys();

        foreach ($keys as $key) {
            $val = $this->$key;
            if ($val instanceof Carbon) {
                $val = $val->format('Y-m-d H:i:s');
            }
            $data[$key] = $val;
        }

        return $data;
    }

    public function getArchivableKeys()
    {
        $data = $this->attributes;
        unset($data[$this->getKeyName()]);
        unset($data[static::UPDATED_AT]);

        $keys = array_keys($data);

        return $keys;
    }

    public function nextArchiveVersion()
    {
        $model = $this->getArchiveModel();
        $entity = $model->where('ref_id', $this->getKey())->where('entity', $this->getArchiveEntityString())
                        ->orderBy('version', 'desc')->first()
        ;

        if ($entity) {
            return (int) $entity->version + 1;
        }

        return 1;
    }

    public function versions()
    {
        if (! isset($this->_versions)) {
            $model = $this->getArchiveModel();

            $this->_versions = $model->where('ref_id', $this->getKey())->where('entity', $this->getArchiveEntityString())
                                     ->orderBy('version', 'desc')->get()
            ;
        }

        return $this->_versions;
    }

    public function version($version)
    {
        $model = $this->getArchiveModel();

        return $model// ->where('id', $id)
        ->where('version', $version)// Not necessary but will ensure it's the right object
        ->where('ref_id', $this->getKey())->where('entity', $this->getArchiveEntityString())->orderBy('version', 'desc')
        ->first()
            ;
    }

    public function getLastArchive()
    {
        $model = $this->getArchiveModel();
        $archive = $model// ->select('id, ref_id, entity, data, updated_at, created_at, updated_by_id')
        // ->select('MAX(version) as version')
        // ->where('id', $id)
        // ->where('version', $version)
        // Not necessary but will ensure it's the right object
        ->where('ref_id', $this->getKey())->where('entity', $this->getArchiveEntityString())->orderBy('version', 'desc')
        ->first()
        ;

        return $archive;
    }

    public function versionsList($format = 'Version #{version} - {updated_at}')
    {
        $versions = $this->versions();
        if ($versions) {
            return $versions->keyBy('version')->map(function ($item) use ($format) {
                $str = $format;
                foreach ($item->getAttributes() as $key => $val) {
                    $val = $item->getAttribute($key);
                    if ($val instanceof Carbon) {
                        $val = $val->format(config('snap.admin.date_format'));
                    }
                    if (is_string($val) || is_numeric($val)) {
                        $str = str_replace('{'.$key.'}', $val, $str);
                    }
                }
                return $str;
            });
        }

        return $versions;
    }

    protected function getArchiveModel()
    {
        return new Archive();
    }

    protected function getArchiveEntityString()
    {
        return get_class($this);

        // Problematic with forward slashes so we replace them
        //return str_replace('\\', ':', get_class($this));
    }
}