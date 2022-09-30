<template>
  <div>
    <h1>{{ $t('common.item') }} {{ $t('common.edit') }}</h1>
    <b-card>
      <b-form @submit.stop.prevent="onSubmit">
        <b-form-row>
          <b-col md="6">
            <b-form-group
              id="input-group-label"
              label="Label"
              label-for="input-label"
            >
              <b-form-input
                id="input-label"
                v-model="form.label"
                type="text"
                placeholder="label"
                autofocus
                trim
                required
                :state="formState('label')"
              />
              <b-form-invalid-feedback :state="formState('label')">
                <ErrorsList :errors="formErrors('label')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              id="input-group-categories"
              label="Categories"
              label-for="input-categories"
            >
              <b-form-select
                v-model="form.categories"
                :options="allCategories"
                multiple
                value-field="id"
                text-field="label"
                :select-size="4"
              ></b-form-select>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-button type="submit" variant="primary">
          {{ $t('common.update') }}
        </b-button>
        <b-button
          size="sm"
          variant="danger"
          :aria-label="$t('common.delete')"
          @click="onDelete()"
        >
          <b-icon icon="trash"></b-icon>
        </b-button>
      </b-form>
    </b-card>
  </div>
</template>

<script>
import { ItemQuery } from '@/graphql/items/item.query'
import { UpdateItemMutation } from '@/graphql/items/update_item.mutation'
import { Form } from '@/mixins/form'
import { Roles } from '@/mixins/roles'
import { Locales } from '@/mixins/locales'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Auth } from '@/mixins/auth'
import { Images } from '@/mixins/images'
import { GenericToast } from '@/mixins/generic-toast'
import ErrorsList from '@/components/forms/ErrorsList'
import { DeleteItemMutation } from '@/graphql/items/delete_item.mutation'
import { AllCategoriesQuery } from '@/graphql/categories/categories.query'

export default {
  name: 'ItemEdit',
  components: { ErrorsList },
  mixins: [Form, Roles, Locales, GlobalOverlay, Auth, Images, GenericToast],
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(ItemQuery, {
        id: context.params.id,
      })
      const allCategories = await context.app.$graphql.request(
        AllCategoriesQuery
      )
      return {
        allCategories: allCategories.categories.items,
        form: {
          label: result.item.label,
          categories: result.item.categories.map((category) => category.id),
        },
      }
    } catch (e) {
      context.error(e)
    }
  },
  data() {
    return {
      allCategories: [],
      form: {
        label: '',
        categories: [],
      },
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(UpdateItemMutation, {
          id: this.$route.params.id,
          label: this.form.label,
          categories: this.form.categories.map((category) => {
            return { id: category }
          }),
        })

        this.genericSuccessToast()
        this.$router.push(this.localePath({ name: 'items' }))
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
    async onDelete(id) {
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(DeleteItemMutation, {
          id: this.$route.params.id,
        })

        this.genericSuccessToast()
        this.$router.push(this.localePath({ name: 'items' }))
      } catch (e) {
        this.$nuxt.error(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
