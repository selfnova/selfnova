import {
	AUTH_REQUEST,
	AUTH_ERROR,
	AUTH_SUCCESS,
	AUTH_LOGOUT
} from "../actions/auth";

import HTTP from '@/utils/http';

const state = {
	token: localStorage.getItem("user-token") || "",
	status: "",
	hasLoadedOnce: false
};

const getters = {
	isAuthenticated: state => !!state.token,
	authStatus: state => state.status
};

const actions = {
	[AUTH_REQUEST]: ({ commit, dispatch }, user) => {
		return new Promise((resolve, reject) => {
			commit(AUTH_REQUEST);

            HTTP.post('/login', user)
				.then( resp => {
                    localStorage.setItem("user-token", resp.token);
                    commit(AUTH_SUCCESS, resp);
                    HTTP.setToken();
					resolve(resp);
				})
				.catch(err => {
					commit(AUTH_ERROR, err);
					localStorage.removeItem("user-token");
					reject(err);
				});
		});
	},
	[AUTH_LOGOUT]: ({ commit }) => {
		return new Promise(resolve => {
			commit(AUTH_LOGOUT);
			localStorage.removeItem("user-token");
			resolve();
		});
	}
};

const mutations = {
	[AUTH_REQUEST]: state => {
		state.status = "loading";
	},
	[AUTH_SUCCESS]: (state, resp) => {
		state.status = "success";
		state.token = resp.token;
		state.hasLoadedOnce = true;
	},
	[AUTH_ERROR]: state => {
		state.status = "error";
		state.hasLoadedOnce = true;
	},
	[AUTH_LOGOUT]: state => {
		state.token = "";
	}
};

export default {
	state,
	getters,
	actions,
	mutations
};
