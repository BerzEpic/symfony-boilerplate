import { gql } from 'graphql-request'

export const CreateItemMutation = gql`
  mutation createItem($label: String!, $categories: [CategoryInput!]!) {
    createItem(label: $label, categories: $categories) {
      id
      label
    }
  }
`
