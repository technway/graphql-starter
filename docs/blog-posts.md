<h1>Blog Posts</h1>

This documentation covers the blog features integrated with the **GraphQL Starter theme**. It explains how to query blog posts, implement search functionality, and more. You'll find examples of GraphQL queries and mutations.

- [Main Posts Queries](#main-posts-queries)
  - [Publish Posts](#publish-posts)
  - [Get Posts](#get-posts)
  - [Get Single Post](#get-single-post)
  - [Search Posts](#search-posts)

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
