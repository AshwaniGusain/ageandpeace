<?php

namespace Snap\Form\Processors;

use Illuminate\Http\Request;

class RelationshipToManyProcessor extends AbstractInputProcessor
{
    public function run(Request $request, $resource)
    {
        $relationship = $this->input->key;
        $belongsToMany = $resource->{$relationship}();
        $relatedModel = $belongsToMany->getRelated();

        if ($request->has($relationship)) {
            $ids = array_filter((array) $request->get($relationship));

            if ($belongsToMany->getTable() == 'snap_relationships') {
                $toSync = [];

                $sharedRelationshipInfo = $resource->sharedRelationshipInfo[$relationship] ?? [];

                $tableColumn = $sharedRelationshipInfo['table_column'] ?? 'table';
                $foreignTableColumn = $sharedRelationshipInfo['foreign_table_column'] ?? 'foreign_table';
                $contextColumn = $sharedRelationshipInfo['context_column'] ?? 'context';

                foreach ($ids as $id) {
                    $toSync[$id] = [$tableColumn =>  $resource->getTable(),  $foreignTableColumn => $relatedModel->getTable(), $contextColumn => $this->key];
                }

                $ids = $toSync;
            }

            $belongsToMany->sync($ids);
            $belongsToMany->touch();
        }
    }

}