import {System} from '/script/ecs/ECS.js';
import {BackgroundComponent} from '/script/components/BackgroundComponent.js';

export class BackgroundSystem extends System {
    constructor() {
        super();
        this.requiredComponents = [BackgroundComponent];
    }

    update() {
        for (const entity of this.entities) {
            const backgroundComponent = entity.getComponent(BackgroundComponent);
            if (backgroundComponent) {
                backgroundComponent.sprite.visible = true;
            }
        }
    }
}
