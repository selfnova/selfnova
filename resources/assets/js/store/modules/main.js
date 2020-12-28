import HTTP from '@/utils/http'

import {
	MAIN_LOAD
} from "../actions/main";

const state = {
	status: "",
	user: {}
};

const getters = {
	layout: state => state.layout,
};

const actions = {
	[MAIN_LOAD]: ({ commit, dispatch }) => {
		HTTP.get('/layout')
			.then(resp => {
				commit(MAIN_LOAD, resp);
			})
			.catch(() => {
				commit(USER_ERROR);
				dispatch(AUTH_LOGOUT);
			});
	}
};

const mutations = {
	[MAIN_LOAD]: ( state, resp ) => {
		state.user = resp.user;
	},
};

export default {
	state,
	getters,
	actions,
	mutations
};
