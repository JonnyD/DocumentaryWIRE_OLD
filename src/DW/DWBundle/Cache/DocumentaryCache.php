<?php

namespace DW\DWBundle\Cache;

class DocumentaryCache
{
    const CACHE_DOCUMENTARY = "documentary";
    const CACHE_DOCUMENTARIES = "documentaries";
    const CACHE_CATEGORY_DOCUMENTARIES = "category_documentaries";
    const CACHE_YEAR_DOCUMENTARIES = "year_documentaries";
    const CACHE_DURATION_DOCUMENTARIES = "duration_documentaries";

    const KEY_FEATURED = "featured";
    const KEY_DOCUMENTARY_ID = "documentary_id";
    const KEY_DOCUMENTARY_SLUG = "documentary_slug";
    const KEY_PUBLISHED_DATE_DESC = "published_date_desc";
    const KEY_PUBLISHED_DATE_ASC = "published_date_asc";
    const KEY_PUBLISHED_TITLE_DESC = "published_title_desc";
    const KEY_PUBLISHED_TITLE_ASC = "published_title_asc";
    const KEY_PUBLISHED_VIEWS_DESC = "published_views_desc";
    const KEY_PUBLISHED_VIEWS_ASC = "published_views_asc";
    const KEY_PUBLISHED_COMMENTS_DESC = "published_comments_desc";
    const KEY_PUBLISHED_COMMENTS_ASC = "published_comments_asc";
    const KEY_PUBLISHED_LIKES_DESC = "published_likes_desc";
    const KEY_PUBLISHED_LIKES_ASC = "published_likes_asc";
}