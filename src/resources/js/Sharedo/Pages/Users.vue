<template>
  <div class="mt-10">
    <p class="font-bold text-gray-500">Members</p>
    <owner :user="modelOwner"></owner>
    <div
      v-for="(user, index) in users"
      :key="index"
      class="py-2 mt-1 flex space-x-4"
    >
      <div class="flex items-center">
        <div
          :class="user.ability === 'Owner' ? 'bg-green-500' : 'icon-users'"
          class="inline-block flex items-center justify-center text-white h-9 w-9 rounded-full ring-2 ring-white"
        >
          {{ user.name.charAt(0).toUpperCase() }}
        </div>
      </div>

      <div class="flex flex-col w-1/2">
        <div class="text-sm font-medium">{{ user.name }}</div>
        <div class="text-sm h-full color-75">
          {{ user.email }}
        </div>
      </div>
      <div class="flex-1 flex justify-end float-right">
        <div class="w-28 flex items-center">
          <multiselect
            v-model="usersAbilties[user.id]"
            :id="user.email"
            track-by="value"
            label="ability"
            @select="onSelect"
            placeholder="Select one"
            :options="options"
            :searchable="false"
            :show-labels="false"
            :allow-empty="false"
            :max-height="130"
          >
          </multiselect>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import Owner from "./Owner";
export default {
  components: {
    Multiselect,
    owner: Owner,
  },
  props: {
    entity: {},
    users: Array,
    usersAbilties: {},
    modelOwner: {},
  },
  data() {
    return {
      options: [
        {
          ability: "Can View",
          value: "read",
        },
        {
          ability: "Can Edit",
          value: "write",
        },
        {
          ability: "Remove",
          value: "remove",
        },
      ],
    };
  },
  methods: {
    onSelect: function (selectedOption, index) {
      const emails = [];
      emails.push({
        email: index,
      });
      const data = {
        ability: selectedOption.value,
        emails: emails,
        entity_id: this.entity.id,
        entity_name: this.entity.entity_name,
      };
      let userIndex;
      this.users.forEach((user, idx) => {
        if (user.email === index) {
          userIndex = idx;
        }
      });
      this.users[userIndex].ability = selectedOption.value;
      this.$emit("updateAccess", data);
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.icon-users {
  background-color: #318dd0;
}
/deep/ ul > li:nth-child(3) span span {
  color: red;
}
/deep/ ul > li:nth-child(3) .multiselect__option--highlight span {
  color: white !important;
}
/deep/ .color-75 {
  color: rgba(95, 92, 128, 1);
}
/deep/ .multiselect__option--highlight {
  background: #318dd0 !important;
  color: white !important;
}

/deep/ .multiselect__option {
  color: rgb(55, 52, 97);
  line-height: 8px;
  font-weight: 400;
  font-size: 14px !important;
  min-height: 0px !important;
  padding: 16px 40px 16px 15px;
}

/deep/ .multiselect {
  min-height: 0px !important;
  position: none;
}
/deep/ .multiselect__tag {
  position: static;
  font-size: 4rem !important;
  border: none;
  background: #fff;
}
/deep/ .multiselect__single {
  min-height: 0px !important;
  line-height: unset !important;
  color: black;
  font-size: 14px !important;
  display: unset !important;
}

/deep/ .multiselect__select {
  height: 17px !important;
  line-height: unset !important;
  right: -16px !important;
}
/deep/ .multiselect__content-wrapper {
  width: 150px !important;
  margin-top: 30px;
  border-radius: 5px;
  z-index: 999;
  right: -3px;
  max-height: 600px !important;
  box-shadow: 0 8px 16px 0 rgba(5, 0, 56, 0.12);
}

/deep/ .multiselect__tags {
  font-size: 12.5px;
  border: none;
  min-height: 0px !important;
  line-height: unset !important;
  padding: 0px 20px 0px 0px !important;
  background: #fff;
  float: right;
}
/deep/ .multiselect__tags:hover {
  cursor: pointer;
}
/deep/ .multiselect__option--selected {
  font-weight: normal;
  background-color: white;
}

/deep/ .multiselect__single {
  font-size: 14px;
  font-weight: 600;
  color: #318dd0;
}

/deep/ .multiselect--active .multiselect__select {
  transform: none;
}
/deep/ .multiselect__select:before {
  border-color: #318dd0 transparent transparent transparent;
}
</style>
