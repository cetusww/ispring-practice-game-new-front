import {Component} from '/script/ecs/ECS.js';
import * as PIXI from 'pixijs';
import {StageManager} from "../utils/StageManager";
export class BackgroundComponent extends Component {
    constructor(data) {
        super(data);
        this.texture = PIXI.Texture.from(data.texture);
        this.sprite = new PIXI.Sprite(this.texture);
        this.sprite.width = data.width;
        this.sprite.height = data.height;
        this.sprite.x = data.x;
        this.sprite.y = data.y;
    }

    view() {
        StageManager.addChild(this.sprite);
    }

    deleteView() {
        StageManager.removeChild(this.sprite);
    }
}
