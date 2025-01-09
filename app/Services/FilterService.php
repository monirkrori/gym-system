<?php

namespace App\Services;

class FilterService
{
    /**
     * Apply filters to a query based on the provided filter parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query   The query to apply filters to.
     * @param array                                 $filters The filters to apply, which may include:
     *                                                       - 'search': Search term for user names.
     *                                                       - 'name': Filter by name (partial match).
     *                                                       - 'status': Filter by specific status.
     *                                                       - 'plan_id': Filter by plan ID.
     *                                                       - 'rating_avg': Filter by minimum average rating.
     *                                                       - 'sort_by_rating': Sort by average rating ('asc' or 'desc').
     * @return void
     */
    public function applyFilters($query, $filters)
    {
        // Filter by a search term in the user's name
        if (!empty($filters['search'])) {
            $query->whereHas('user', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter by partial match on the 'name' column
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Filter by exact 'status' match
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by specific 'plan_id'
        if (!empty($filters['plan_id'])) {
            $query->where('plan_id', $filters['plan_id']);
        }

        // Filter by minimum average rating
        if (!empty($filters['rating_avg'])) {
            $query->where('rating_avg', '>=', $filters['rating_avg']);
        }

        // Sort results by 'rating_avg' in ascending or descending order
        if (!empty($filters['sort_by_rating'])) {
            $query->orderBy('rating_avg', $filters['sort_by_rating']);
        }
    }
}
