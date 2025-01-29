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