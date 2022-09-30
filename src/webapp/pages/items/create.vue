<template>
  <b-card>
    <h1>{{ $t('common.item') }} {{ $t('common.create') }}</h1>
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
        {{ $t('common.create') }}
      </b-button>
    </b-form>
  </b-card>
</template>

<script>
import ErrorsList from '@/components/forms/ErrorsList'
import { Form } from '@/mixins/form'
import { Roles } from '@/mixins/roles'
import { Locales } from '@/mixins/locales'
import { CreateItemMutation } from '@/graphql/items/create_item.mutation'
import { AllCategoriesQuery } from '@/graphql/categories/categories.query'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { GenericToast } from '@/mixins/generic-toast'

export default {
  name: 'ItemCreate',
  components: { ErrorsList },
  mixins: [Form, Roles, Locales, GlobalOverlay, GenericToast],
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(AllCategoriesQuery)

      return {
        allCategories: result.categories.items,
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
        await this.$graphql.request(CreateItemMutation, {
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
  },
}
</script>
