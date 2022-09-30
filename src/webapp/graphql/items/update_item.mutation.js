import { gql } from 'graphql-request'

export const UpdateItemMutation = gql`
  mutation updateItem(
    $id: Int!
    $label: String!
    $categories: [CategoryInput!]!
  ) {
    updateItem(item: { id: $id }, label: $label, categories: $categories) {
      id
      label
    }
  }
`
