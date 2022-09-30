import { gql } from 'graphql-request'

export const CategoryQuery = gql`
  query category($id: Int!) {
    category(category1: { id: $id }) {
      id
      label
    }
  }
`
