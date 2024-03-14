import BaseApi from "./base.api";
import Http from "@services/http";

export default class CompanyApi extends BaseApi {
    static get entity() {
        return "companies";
    }
}
