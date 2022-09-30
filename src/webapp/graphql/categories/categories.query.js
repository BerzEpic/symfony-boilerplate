import { gql } from 'graphql-request'

export const CategoriesQuery = gql`
  query categories($search: String, $limit: Int!, $offset: Int!) {
    categories(search: $search) {
      items(limit: $limit, offset: $offset) {
        id
        label
      }
      count
    }
  }
`
export const AllCategoriesQuery = gql`
  query categories {
    categories {
      items {
        id
        label
      }
      count
    }
  }
`
