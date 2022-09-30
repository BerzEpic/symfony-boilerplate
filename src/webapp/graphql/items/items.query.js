import { gql } from 'graphql-request'

export const ItemsQuery = gql`
  query items($search: String, $limit: Int!, $offset: Int!) {
    items(search: $search) {
      items(limit: $limit, offset: $offset) {
        id
        label
        categories {
          id
          label
        }
      }
      count
    }
  }
`
