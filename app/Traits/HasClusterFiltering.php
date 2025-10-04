<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasClusterFiltering
{
    /**
     * Apply cluster filter based on authenticated user's clusters
     */
    public function scopeForAuthUserClusters(Builder $query): Builder
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query;
        }
        
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // If user has specific clusters, filter by them
        if (!empty($userClusterIds)) {
            return $query->whereIn('cluster_id', $userClusterIds);
        }
        
        // If user has no clusters assigned, return all (admin case)
        return $query;
    }
    
    /**
     * Check if user has access to specific cluster
     */
    public static function userHasAccessToCluster(int $clusterId): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // If no clusters assigned, has access to all
        if (empty($userClusterIds)) {
            return true;
        }
        
        return in_array($clusterId, $userClusterIds);
    }
}