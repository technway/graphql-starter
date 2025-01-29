<h1>Blog Posts</h1>

This documentation covers the blog features integrated with the **GraphQL Starter theme**. It explains how to query blog posts, implement search functionality, and more. You'll find examples of GraphQL queries and mutations.

- [Main Posts Queries](#main-posts-queries)
  - [Publish Posts](#publish-posts)
  - [Get Posts](#get-posts)
  - [Get Single Post](#get-single-post)
  - [Search Posts](#search-posts)
- [Comments](#comments)
  - [Add Comment](#add-comment)
  - [Get Post Comments](#get-post-comments)
- [Paginate Posts](#paginate-posts)
  - [Understanding Cursor Pagination](#understanding-cursor-pagination)
  - [Basic Pagination Flow](#basic-pagination-flow)
  - [Implementation Guide](#implementation-guide)
  - [Getting Total Pages Count](#getting-total-pages-count)
    - [Response Example](#response-example)
    - [Important Notes:](#important-notes)
    - [Error Cases:](#error-cases)
  - [Common Patterns](#common-patterns)

## Main Posts Queries

### Publish Posts

For now it's not possible to publish posts using GraphQL. The only way to publish posts is to use the WordPress Interface.
So there is no available GraphQL mutation to publish posts.

### Get Posts

To get posts, you can use the `posts` query. The folowing query contains some example fields that you can use to get posts.

```graphql
query GetPosts {
    posts {
        nodes {
            id
            databaseId
            title
            content
            date
            slug
            excerpt
            categories {
                nodes {
                    name
                    slug
                }
            }
            featuredImage {
                node {
                    sourceUrl
                    altText
                }
            }
            author {
                node {
                    name
                    description
                    avatar {
                        url
                    }
                }
            }
        }
    }
}
```

### Get Single Post

To get a single post, you can use the `post` query.

```graphql
# Query
query GetPost($id: ID!) {
    post(id: $id) {
        id
        title
    }
}

# Variables
{
    "id": "cG9zdDox"
}
```

### Search Posts

To search posts, you can use the `posts` query with the `where` argument.

```graphql
# Query
query SearchPosts($search: String!) {
    posts(where: {search: $search}) {
        nodes {
            id
            title
            excerpt
            slug
            date
            categories {
            nodes {
                name
            }
            }
            featuredImage {
            node {
                sourceUrl
                altText
            }
            }
            author {
            node {
                name
            }
            }
        }
    }
}

# Variables
{
    "search": "Hello"
}
```

## Comments

The GraphQL Starter theme provides functionality to work with post comments. Here are the available queries and mutations:

### Add Comment
To add a comment to a post, use the `createComment` mutation:

```graphql
# Query
mutation AddComment($input: CreateCommentInput!) {
    createComment(input: $input) {
        success
        comment {
            id
            content
            date
            author {
                node {
                    name
                }
            }
        }
    }
}

# Variables
{
    "input": {
        "commentOn": 1, # Post Database ID to comment on
        "content": "Great post!",
        "author": "John Doe",
        "authorEmail": "john@example.com"
    }
}
```

### Get Post Comments
To get comments for a specific post:

```graphql
# Query
query GetPostComments($id: ID!) {
    post(id: $id, idType: DATABASE_ID) {
        id
        title
        comments(first: 100) {
            nodes {
            id
            content
            parentId
            }
        }
    }
}

# Variables
{
    "id": 1  # Post Database ID
}
```

---

## Paginate Posts

WPGraphQL uses cursor-based pagination for posts, which is more reliable than offset-based pagination for large datasets.

### Understanding Cursor Pagination

1. **How It Works**
   - Instead of page numbers, cursors are used to track position
   - Each item has a unique cursor (base64 encoded identifier)
   - Navigation is done by requesting items after a specific cursor

2. **Key Concepts**
   - `first`: Number of items to fetch (like "items per page")
   - `after`: Cursor pointing to the item after which to start
   - `hasNextPage`: Indicates if more items exist
   - `endCursor`: Cursor of the last item in current result set

### Basic Pagination Flow

1. **First Page Query**
```graphql
query GetPaginatedPosts($first: Int!) {
    posts(first: $first) {
        nodes {
            id
            title
            excerpt
        }
        pageInfo {
            hasNextPage
            endCursor
        }
    }
}

# Variables
{
    "first": 1
}

# Example Response
{
    "data": {
        "posts": {
            "nodes": [
                {
                    "id": "cG9zdDoxNA==",
                    "title": "First Post",
                    "excerpt": "..."
                }
            ],
            "pageInfo": {
                "hasNextPage": true,
                "endCursor": "YXJyYXljb25uZWN0aW9uOjE0"
            }
        }
    }
}
```

2. **Next Page Query**
```graphql
query GetPaginatedPosts($first: Int!, $after: String) {
    posts(first: $first, after: $after) {
        nodes {
            id
            title
            excerpt
        }
        pageInfo {
            hasNextPage
            endCursor
        }
    }
}

# Variables
{
    "first": 1,
    "after": "YXJyYXljb25uZWN0aW9uOjE0"
}
```

### Implementation Guide

1. **Initial Load**
   - Start with no `after` parameter
   - Store the `endCursor` from response
   - Check `hasNextPage` to show/hide "Load More" button

2. **Loading More**
   - Use stored `endCursor` as `after` parameter
   - Append new items to existing list
   - Update stored cursor with new `endCursor`
   - Repeat until `hasNextPage` is `false`

3. **Dynamic Loading Example**
```javascript
let endCursor = null;
let hasNextPage = true;

async function loadPosts() {
    const query = `
        query GetPaginatedPosts($first: Int!, $after: String) {
            posts(first: $first, after: $after) {
                nodes {
                    id
                    title
                    excerpt
                }
                pageInfo {
                    hasNextPage
                    endCursor
                }
            }
        }
    `;

    const variables = {
        first: 10,
        after: endCursor
    };

    const response = await fetchGraphQL(query, variables);
    const { nodes, pageInfo } = response.data.posts;

    // Update cursors
    endCursor = pageInfo.endCursor;
    hasNextPage = pageInfo.hasNextPage;

    return nodes;
}
```

### Getting Total Pages Count

The `total` field in `pageInfo` returns the total number of pages based on your pagination settings. This is useful for building pagination UI components.

```graphql
# Query
query GetPostsCounts($first: Int!) {
    posts(first: $first) {
        pageInfo {
            total    # Total number of pages
        }
    }
}

# Variables
{
    "first": 1       # Number of posts per page
}
```

#### Response Example

*3 posts with 1 post per page = 3 pages*

```json
{
    "data": {
        "posts": {
            "pageInfo": {
                "total": 3    
            }
        }
    }
}
```

#### Important Notes:
- The `first` parameter is required and determines posts per page
- Valid range for `first`: 1-100 posts per page
- `total` represents total number of pages
- Example calculation: 3 total posts with 1 post per page = 3 pages
- To use the `total` ensure that the constant `GRAPHQL_STARTER_POST_PAGES_COUNT_ENABLED` is set to `true` in the `functions.php` file.

#### Error Cases:
- `GRAPHQL_STARTER_POST_PAGES_COUNT_ENABLED` is set to `false`: "Cannot query field \"total\" on type \"RootQueryToPostConnectionPageInfo\"."
- Missing `first` parameter: "The "first" parameter is required for pagination calculation."
- Invalid `first` value: "The "first" parameter must be a numeric value"
- Zero or negative: "The "first" parameter must be greater than 0"
- Too large: "The "first" parameter cannot exceed 100 items per page"

> Note: The `total` field is not available in the `pageInfo` object when constant `GRAPHQL_STARTER_POST_PAGES_COUNT_ENABLED` is set to `false`.
> So set it to false if you don't need the total pages count.

### Common Patterns

1. **Infinite Scroll**
```javascript
window.addEventListener('scroll', () => {
    if (
        hasNextPage &&
        window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000
    ) {
        loadPosts();
    }
});
```

2. **Load More Button**
```html
<button 
    onclick="loadPosts()"
    disabled={!hasNextPage}
>
    {hasNextPage ? 'Load More' : 'No More Posts'}
</button>
```