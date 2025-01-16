# Testing GraphQL Queries

This document provides instructions for testing GraphQL queries using the `.http` file in the **REST Client** extension for VS Code. 

## Prerequisites

- Install the **REST Client** extension for VS Code.
- Ensure you have a `.env` file containing the base URL for your GraphQL server, defined as `BASE_URL`.

## Environment Variable Setup

To use the environment variable `BASE_URL` in your `.http` file, you need to define it in your `.env` file. Make sure your `.env` file includes:

```dotenv
BASE_URL=http://your-graphql-server-url
```

Replace `http://your-graphql-server-url` with the actual GraphQL server Base URL.

## How to Add a New Query Test

To add a new query test to the `.http` file, follow these steps:

### 1. Add the Query to the `.http` File

Once the query is created, add it to the `.http` file. You can follow the same structure as the existing queries.

For example, to add a **Get Posts** query, it would look like this:

```http
### Get Posts
POST {{baseURL}}/graphql HTTP/1.1
Content-Type: application/json

{
  "query": "query { posts { nodes { id title content } } }"
}
```

### 2. Add Variables (If Needed)

If your query requires variables, you can include them just like in the previous examples. For instance, if you want to pass an `id` variable to fetch a specific post, your request could look like this:

```http
### Get Post By Id
POST {{baseURL}}/graphql HTTP/1.1
Content-Type: application/json

{
  "query": "query getPost($id: ID!) { post(id: $id) { id title } }",
  "variables": {"id": "cG9zdDox"}
}
```

## How to Run the Queries

1. Open or create a `.http` file in Visual Studio Code.
2. Make sure the `.env` file is correctly set up with the `BASE_URL`.
3. Click on the **Send Request** link above each query in the `.http` file. The REST Client will execute the request and display the response in the results pane.
4. Ensure that the response returns the expected data.

If there are any errors in the query, they will be displayed in the `errors` field of the response.

## Troubleshooting

If you're encountering issues, here are a few things to check:

- Ensure the `BASE_URL` is set correctly in the `.env` file.
- Check the GraphQL server for any authentication or CORS issues that may prevent successful queries.
- Do not try to break lines in the query. For example, do not do this:
```http
{
  "query": "query {
    posts { 
        nodes {
            id 
            title 
            content 
            }
        }
    }"
}
```
Instead, do this:
```http
{
  "query": "query { posts { nodes { id title content } } }"
}
```
