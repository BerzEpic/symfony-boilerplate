import { gql } from 'graphql-request'

export const DeleteItemMutation = gql`
  mutation deleteItem($id: Int!) {
    deleteItem(item1: { id: $id })
  }
`
