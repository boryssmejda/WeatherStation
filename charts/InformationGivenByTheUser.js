class InformationGivenByTheUser
{
    constructor(city, physicalQuantities, beginning, end) {
        this.city = city;
        this.physicalQuantities = physicalQuantities;
        this.beginning = beginning;
        this.end = end;

        console.log("In constructor!");
        console.log(this.physicalQuantities);
    }
}
