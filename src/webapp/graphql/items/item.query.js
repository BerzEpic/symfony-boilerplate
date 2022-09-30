import { gql } from 'graphql-request'

export const ItemQuery = gql`
  query item($id: Int!) {
    item(item1: { id: $id }) {
      id
      label
      categories {
        id
        label
      }
    }
  }
`
