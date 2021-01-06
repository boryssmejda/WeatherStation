class InputParameters
{
    constructor()
    {
        this.city = this.getCity();
        this.physicalQuantities = this.getPhysicalQuantities();
        this.beginning = this.getBeginning();
        this.end = this.getEnd();
    }

    getCity()
    {
        return document.querySelector('input[name="city"]:checked').value;
    }

    getPhysicalQuantities()
    {
        let physical_quantities = [];
        const chosenPhysicalQuantities = document.querySelectorAll('input[name="physical_quantity"]:checked');

        chosenPhysicalQuantities.forEach(element => {
            physical_quantities.push(element.value);
        });

        return physical_quantities;
    }

    getBeginning()
    {
        return getDateTime("weatherConditionsBeginning");
    }

    getEnd()
    {
        return getDateTime("weatherConditionsEnd");
    }

    getDateTime(current_datetime)
    {
        return document.getElementById(current_datetime).value;
    }
}
